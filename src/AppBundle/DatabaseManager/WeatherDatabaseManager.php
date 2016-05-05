<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 17/04/16
 * Time: 20:05
 */

namespace AppBundle\DatabaseManager;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\City;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
use AppBundle\Entity\Temperature;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class WeatherDatabaseManager
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * WeatherPersister constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Persist weather data
     * @param array $temperatures
     */
    public function persist(array $temperatures)
    {
        foreach ($temperatures as $temperature) {
            switch ($temperature['type']) {
                case 'forecast':
                    $forecast = $this->makeForecastObject($temperature);
                    $entityManager = $this->managerRegistry->getManagerForClass(get_class($forecast));
                    $entityManager->persist($forecast);
                    break;
                case 'current':
                    $current = $this->makeTemperatureObject($temperature);
                    $entityManager = $this->managerRegistry->getManagerForClass(get_class($current));
                    $entityManager->persist($current);
                    break;
            }
        }
    }

    /**
     * Persist and flush weather data
     * @param array $temperatures
     */
    public function persistAndFlush(array $temperatures)
    {
        foreach ($temperatures as $temperature) {
            switch ($temperature['type']) {
                case 'forecast':
                    $forecast = $this->makeForecastObject($temperature);
                    $entityManager = $this->managerRegistry->getManagerForClass(get_class($forecast));
                    $entityManager->persist($forecast);
                    $entityManager->flush();
                    break;
                case 'current':
                    $current = $this->makeTemperatureObject($temperature);
                    $entityManager = $this->managerRegistry->getManagerForClass(get_class($current));
                    $entityManager->persist($current);
                    $entityManager->flush();
                    break;
            }
        }
    }

    /**
     * Flush weather data to DB
     */
    public function flush()
    {
        $entityManager = $this->managerRegistry->getManager();
        try {
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            error_log($e->getMessage());
        }
    }


    /**
     * Get all cities from DB
     * @return array
     */
    public function fetchCities()
    {
        $entityManager = $this->managerRegistry->getManager();
        $cities = $entityManager->getRepository('AppBundle:City')->findAll();
        return $cities;
    }

    /**
     * Fetch provider from DB
     * @param string $providerName
     * @return Provider
     * @throws WeatherProviderException
     */
    public function fetchProvider(string $providerName)
    {
        $entityManager = $this->managerRegistry->getManager();
        $provider = $entityManager
            ->getRepository('AppBundle:Provider')
            ->findOneBy(array('name' => $providerName));

        if (!($provider instanceof Provider)) {
            throw new WeatherProviderException(sprintf('Can\'t get %s provider from DB', $providerName));
        }

        return $provider;
    }

    /**
     * Fetch all providers from DB
     * @return array
     */
    public function fetchProviders()
    {
        $entityManager = $this->managerRegistry->getManager();
        $providers = $entityManager->getRepository('AppBundle:Provider')->findAll();
        return $providers;
    }

    /**
     * Make forecast object
     * @param array $forecast
     * @return Forecast
     */
    private function makeForecastObject(array $forecast): Forecast
    {
        $forecastObject = new Forecast();
        $forecastObject->setProvider($this->fetchProvider($forecast['provider']));
        $forecastObject->setForecastDate($forecast['forecastDate']);
        $forecastObject->setCity($this->fetchCityObject($forecast['cityId']));
        $forecastObject->setForecastDays($forecast['forecastDays']);
        $forecastObject->setTemperatureHigh($forecast['temperatureHigh']);
        $forecastObject->setTemperatureLow($forecast['temperatureLow']);
        if (isset($forecast['humidity'])) {
            $forecastObject->setHumidity($forecast['humidity']);
        }
        if (isset($forecast['pressure'])) {
            $forecastObject->setPressure($forecast['pressure']);
        }

        return $forecastObject;
    }


    /**
     * Make temperature object
     * @param array $current
     * @return Temperature
     */
    private function makeTemperatureObject(array $current): Temperature
    {
        $temperatureObject = new Temperature();
        $temperatureObject->setDate($current['currentDate']);
        $temperatureObject->setCity($this->fetchCityObject($current['cityId']));
        $temperatureObject->setTemperature($current['temperatureCurrent']);
        $temperatureObject->setProvider($this->fetchProvider($current['provider']));
        if (isset($current['humidity'])) {
            $temperatureObject->setHumidity($current['humidity']);
        }
        if (isset($current['pressure'])) {
            $temperatureObject->setPressure($current['pressure']);
        }

        return $temperatureObject;
    }

    /**
     * Fetch City object
     * @param int $id
     * @return City
     * @throws \Exception
     */
    private function fetchCityObject(int $id): City
    {
        $entityManager = $this->managerRegistry->getManager();
        $city = $entityManager->getRepository('AppBundle:City')->findOneBy(array('id' => $id));

        return $city;
    }
}
