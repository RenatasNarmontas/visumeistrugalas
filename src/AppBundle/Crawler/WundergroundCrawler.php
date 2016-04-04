<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 01/04/16
 * Time: 22:20
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
use AppBundle\Entity\Temperature;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WundergroundCrawler implements CrawlerInterface
{
    private $em;
    private $wuApi;
    private $provider;
    private $city;

    /**
     * WundergroundCrawler constructor.
     * @param ContainerInterface $containerInterface
     * @throws \Exception
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->em = $containerInterface->get('doctrine')->getManager();
        if (null === $this->em) {
            throw new \Exception('Can\'t get Doctrine entity manager');
        }

        // Get provider
        $this->provider = $this->getProvider();

        if (null === $this->provider) {
            throw new \Exception('Can\'t find provider ID');
        }

        // Read wunderground API key from config
        $this->wuApi = $containerInterface->getParameter('wundergroung_api');

        // Check API key
        if (null === $this->wuApi) {
            throw new \Exception('Wunderground API key is missing');
        }
    }

    /**
     * Crawl wunderground site and save to DB
     */
    public function crawl()
    {
        $citiesAndCountries = $this->getCitiesAndCountries();

        foreach ($citiesAndCountries as $city => $properties) {
        // Get content
            $json_string = file_get_contents(
                sprintf(
                    'http://api.wunderground.com/api/%s/forecast10day/conditions/q/%s/%s.json',
                    $this->wuApi,
                    $properties[1],
                    $city
                )
            );

            // Parse json
            $parsed_json = json_decode($json_string);

            // Convert forecast date from UNIX timestamp to PHP DateTime
            $epoch = $parsed_json->forecast->simpleforecast->forecastday[0]->date->epoch;
            $dt = new \DateTime();
            $dt->setTimestamp($epoch);

            $i = 0;
            foreach ($parsed_json->forecast->simpleforecast->forecastday as $forecastItem) {
                // Skip forecast for the current day
                if (0 === $i) {
                    $i++;
                    continue;
                }

                // Parse and save forecast conditions
                $forecastObject = $this->makeForecastObject($forecastItem, $dt, $properties[0], $i);
                $this->persistForecast($forecastObject);

                // We need 5 days forecast only
                if (5 === $i) {
                    break;
                }
                $i++;
            }

            // Parse and save current conditions
            $temperatureObject = $this->makeTemperatureObject($parsed_json, $dt, $properties[0]);
            $this->persistTemperature($temperatureObject);
        }

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Make forecast object
     * @param $forecastItem
     * @param $dt
     * @param $cityId
     * @param $i
     * @return Forecast
     */
    private function makeForecastObject($forecastItem, $dt, $cityId, $i)
    {
        $forecastObject = new Forecast();
        $forecastObject->setProvider($this->provider);
        $forecastObject->setForecastDate($dt);
        $forecastObject->setCity($this->getCityObject($cityId));
        $forecastObject->setForecastDays($i);
        $forecastObject->setTemperatureHigh($forecastItem->high->celsius);
        $forecastObject->setTemperatureLow($forecastItem->low->celsius);
        $forecastObject->setHumidity($forecastItem->avehumidity);

        return $forecastObject;
    }

    /**
     * Make temperature object
     * @param $parsed_json
     * @param $dt
     * @param $cityId
     * @return Temperature
     */
    private function makeTemperatureObject($parsed_json, $dt, $cityId)
    {
        $conditionsObject = new Temperature();

        $conditionsObject->setDate($dt);

        $conditionsObject->setCity($this->getCityObject($cityId));
        $conditionsObject->setTemperatureHigh($parsed_json->current_observation->temp_c);
        $conditionsObject->setTemperatureLow($parsed_json->current_observation->temp_c);
        $conditionsObject->setProvider($this->provider);
        $conditionsObject->setHumidity($parsed_json->current_observation->relative_humidity);
        $conditionsObject->setPressure($parsed_json->current_observation->pressure_mb);

        return $conditionsObject;
    }

    /**
     * Get all cities and countries from DB
     * Format: City -> (CityId, Country)
     * [
     *   ('Roma' => (1, 'Italy')),
     *   ...
     * ]
     *
     * @return array
     */
    private function getCitiesAndCountries()
    {
        $cities = $this->em->getRepository('AppBundle:City')->findAll();

        $data = array();
        foreach ($cities as $city) {
            $data[$this->normalizeCityName($city->getName())] = array($city->getId(), $city->getCountry());
        }

        return $data;
    }

    /**
     * Get provider
     *
     * @return Provider
     */
    private function getProvider()
    {
        return $this->em->getRepository('AppBundle:Provider')->findOneBy(array('name' => 'wunderground'));
    }

    /**
     * Normalizes city name
     *
     * @param string $cityName City name for normalization
     * @return string
     */
    private function normalizeCityName(string $cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }

    /**
     * Persist forecast
     *
     * @param Forecast $forecast
     */
    private function persistForecast(Forecast $forecast)
    {
        $this->em->persist($forecast);
    }

    /**
     * Persist temperature
     *
     * @param Temperature $temperature
     */
    private function persistTemperature(Temperature $temperature)
    {
        $this->em->persist($temperature);
    }

    /**
     * Get City object
     *
     * @param int $id
     * @return City
     */
    private function getCityObject(int $id)
    {
        if ((null === $this->city) || ($id !== $this->city->getId())) {
            $this->city = $this->em->getRepository('AppBundle:City')->findOneBy(array('id' => $id));
        }
        return $this->city;
    }
}
