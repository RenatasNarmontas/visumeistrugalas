<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/05/16
 * Time: 02:17
 */

namespace AppBundle\DatabaseManager;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\City;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
use Doctrine\ORM\EntityManager;

/**
 * Class DatabaseManagerAbstract
 * @package AppBundle\DatabaseManager
 */
abstract class DatabaseManagerAbstract
{
    /**
     * @param EntityManager $entityManager
     * @param string $providerId
     * @param string $forecastDate
     * @param string $forecastDays
     * @param string $cityId
     * @param string $temperatureHigh
     * @param string $temperatureLow
     * @param string $temperatureHighDeviation
     * @param string $temperatureLowDeviation
     * @param string $humidity
     * @param string $humidityDeviation
     * @param string $pressure
     * @param string $pressureDeviation
     * @return Forecast
     * @throws WeatherProviderException
     */
    public function makeForecastObject(
        EntityManager $entityManager,
        string $providerId,
        string $forecastDate,
        string $forecastDays,
        string $cityId,
        string $temperatureHigh = null,
        string $temperatureLow = null,
        string $temperatureHighDeviation = null,
        string $temperatureLowDeviation = null,
        string $humidity = null,
        string $humidityDeviation = null,
        string $pressure = null,
        string $pressureDeviation = null
    ): Forecast {
        // Check required parameters first
        if (!isset($providerId)) {
            throw new WeatherProviderException('Provider ID can\'t be null');
        }
        if (!isset($forecastDate)) {
            throw new WeatherProviderException('Forecast date can\'t be null');
        }
        if (!isset($forecastDays)) {
            throw new WeatherProviderException('Forecast days can\'t be null');
        }
        if (!isset($cityId)) {
            throw new WeatherProviderException('City ID can\'t be null');
        }

        $forecast = new Forecast();
        // Mandatory fields
        $forecast->setCity($this->fetchCityObject($entityManager, $cityId));
        $forecast->setForecastDate(new \DateTime($forecastDate));
        $forecast->setForecastDays($forecastDays);
        $forecast->setProvider($this->fetchProviderObject($entityManager, $providerId));

        // Optional fields
        if (isset($temperatureHigh)) {
            $forecast->setTemperatureHigh($temperatureHigh);
        }
        if (isset($temperatureLow)) {
            $forecast->setTemperatureLow($temperatureLow);
        }
        if (isset($temperatureHighDeviation)) {
            $forecast->setTemperatureHighDeviation($temperatureHighDeviation);
        }
        if (isset($temperatureLowDeviation)) {
            $forecast->setTemperatureLowDeviation($temperatureLowDeviation);
        }
        if (isset($humidity)) {
            $forecast->setHumidity($humidity);
        }
        if (isset($humidityDeviation)) {
            $forecast->setHumidityDeviation($humidityDeviation);
        }
        if (isset($pressure)) {
            $forecast->setPressure($pressure);
        }
        if (isset($pressureDeviation)) {
            $forecast->setPressureDeviation($pressureDeviation);
        }

        return $forecast;
    }

    /**
     * Fetch city
     * @param EntityManager $entityManager
     * @param string $cityId
     * @return City
     * @throws WeatherProviderException
     */
    private function fetchCityObject(EntityManager $entityManager, string $cityId): City
    {
        /** @var City $city */
        $city = $entityManager->getRepository('AppBundle:City')->findOneById($cityId);

        if (!isset($city)) {
            throw new WeatherProviderException(
                sprintf(
                    'Can\'t fetch City from DB. City ID = %s',
                    $cityId
                )
            );
        }

        return $city;
    }

    /**
     * Fetch provider
     * @param EntityManager $entityManager
     * @param string $providerId
     * @return Provider
     * @throws WeatherProviderException
     */
    private function fetchProviderObject(EntityManager $entityManager, string $providerId): Provider
    {
        /** @var Provider $provider */
        $provider = $entityManager->getRepository('AppBundle:Provider')->findOneById($providerId);

        if (!isset($provider)) {
            throw new WeatherProviderException(
                sprintf(
                    'Can\'t fetch Provider from DB. Provider ID = %s',
                    $providerId
                )
            );
        }

        return $provider;
    }

}
