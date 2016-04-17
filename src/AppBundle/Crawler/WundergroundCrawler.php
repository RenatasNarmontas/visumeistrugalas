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

class WundergroundCrawler implements CrawlerInterface
{
    /**
     * @var string
     */
    private $wundergroundApiKey;

    /**
     * WundergroundCrawler constructor.
     * @param string $wundergroundApiKey
     */
    public function __construct(string $wundergroundApiKey)
    {
        $this->wundergroundApiKey = $wundergroundApiKey;
    }

    /**
     * Crawl wunderground site and save to DB
     * @param Provider $provider
     * @param City $city
     * @return array
     * @throws WeatherProviderException
     */
    public function crawl(Provider $provider, City $city): array
    {
        // Array of current and forecast temperatures
        $result = array();

        // Get content
        $json_string = file_get_contents(
            sprintf(
                'http://api.wunderground.com/api/%s/forecast10day/conditions/q/%s/%s.json',
                $this->wundergroundApiKey,
                $city->getCountry(),
                $this->normalizeCityName($city->getName())
            )
        );

        // Parse json
        $parsed_json = json_decode($json_string);

        if (isset($parsed_json->response->error->type)) {
            throw new WeatherProviderException(
                sprintf(
                    'JSON response error: %s - %s',
                    $parsed_json->response->error->type,
                    $parsed_json->response->error->description
                )
            );
        }

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
            $forecastObject = $this->makeForecastObject($provider, $forecastItem, $dt, $city, $i);
            array_push($result, $forecastObject);

            // We need 5 days forecast only
            if (5 === $i) {
                break;
            }
            $i++;
        }

        // Parse and save current conditions
        $temperatureObject = $this->makeTemperatureObject($provider, $parsed_json, $dt, $city);
        array_push($result, $temperatureObject);

        return $result;
    }

    /**
     * Make forecast object
     * @param Provider $provider
     * @param $forecastItem
     * @param \DateTime $dt
     * @param City $city
     * @param int $i
     * @return Forecast
     */
    private function makeForecastObject($provider, $forecastItem, $dt, $city, $i)
    {
        $forecastObject = new Forecast();
        $forecastObject->setProvider($provider);
        $forecastObject->setForecastDate($dt);
        $forecastObject->setCity($city);
        $forecastObject->setForecastDays($i);
        $forecastObject->setTemperatureHigh($forecastItem->high->celsius);
        $forecastObject->setTemperatureLow($forecastItem->low->celsius);
        $forecastObject->setHumidity($forecastItem->avehumidity);

        return $forecastObject;
    }

    /**
     * Make temperature object
     * @param Provider $provider
     * @param $parsed_json
     * @param \DateTime $dt
     * @param City $city
     * @return Temperature
     */
    private function makeTemperatureObject($provider, $parsed_json, $dt, $city)
    {
        $conditionsObject = new Temperature();
        $conditionsObject->setDate($dt);
        $conditionsObject->setCity($city);
        $conditionsObject->setTemperatureHigh($parsed_json->current_observation->temp_c);
        $conditionsObject->setTemperatureLow($parsed_json->current_observation->temp_c);
        $conditionsObject->setProvider($provider);
        $conditionsObject->setHumidity($parsed_json->current_observation->relative_humidity);
        $conditionsObject->setPressure($parsed_json->current_observation->pressure_mb);

        return $conditionsObject;
    }

    /**
     * Normalizes city name
     * @param string $cityName City name for normalization
     * @return string
     */
    private function normalizeCityName(string $cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }
}
