<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 20/04/16
 * Time: 20:01
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;

abstract class CrawlerAbstract implements CrawlerInterface
{
    const DATA_TYPE = 'type';
    const PROVIDER = 'provider';
    const CURRENT_DATE = 'currentDate';
    const FORECAST_DATE = 'forecastDate';
    const FORECAST_DAYS = 'forecastDays';
    const CITY_ID = 'cityId';
    const TEMPERATURE_CURRENT = 'temperatureCurrent';
    const TEMPERATURE_HIGH = 'temperatureHigh';
    const TEMPERATURE_LOW = 'temperatureLow';
    const HUMIDITY = 'humidity';
    const PRESSURE = 'pressure';
    
    /**
     * Crawls the site and returns array of forecast and current temperature for the city
     * @param City $city
     * @return array
     */
    abstract public function crawl(City $city): array;

    /**
     * Normalizes city name
     * @param string $cityName City name for normalization
     * @return string
     */
    protected function normalizeCityName(string $cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }
}
