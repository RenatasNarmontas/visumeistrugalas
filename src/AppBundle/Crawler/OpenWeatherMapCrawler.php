<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.5.7
 * Time: 14.32
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;

class OpenWeatherMapCrawler extends CrawlerAbstract
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Crawls the site and returns array of forecast and current temperature for the city
     * @param City $city
     * @return array
     */
    public function crawl(City $city): array
    {
        $forecastUri = 'http://api.openweathermap.org/data/2.5/forecast/daily?q='
               .$city->getName().','.$city->getCountryIso3166() .'&mode=json&units=metric&cnt=5&appid='.$this->apiKey;
        $currentWeatherUri = 'http://api.openweathermap.org/data/2.5/weather?q='
               .$city->getName().','.$city->getCountryIso3166() .'&appid=' . $this->apiKey . '&units=metric';

        $forecastJsonString = file_get_contents($forecastUri);
        $currentWeatherJsonString = file_get_contents($currentWeatherUri);

        $forecastArray = $this->getJsonArray($forecastJsonString);
        $currentWeatherArray = $this->getJsonArray($currentWeatherJsonString);

        $forecasts = $this->getForecastData($forecastArray, $city);
        $currentWeather = $this->getCurrentData($currentWeatherArray, $city);

        $result = array_push($forecasts, $currentWeather);

        return $result;

    }

    /**
     * Convert json string into associative array
     * @param string $jsonString
     * @return array
     */
    private function getJsonArray(string $jsonString):array
    {
        $data = json_decode($jsonString, true);
        if (!$data) {
            $error = error_get_last();
            throw new WeatherProviderException(
                sprintf(
                    'HTTP request error: %s',
                    $error['message']
                )
            );
        }
        if (isset($data->cod) && $data->cod !== 200) {
            throw new WeatherProviderException('The request was unsuccessful');
        }

        return $data;
    }

    private function convertDate($unixTimestamp)
    {
        $date = new \DateTime();
        $date->setTimestamp($unixTimestamp);
        return $date;
    }

    /**
     * Get weather forecast data
     * @param array $parsedJson
     * @param City $city
     * @return array
     */
    private function getForecastData(array $parsedJson, City $city): array
    {
        $forecasts = array();

        for ($i=0; $i<5; $i++) {
            $dayCounter=$i;
            $forecasts[] = [
                $this::DATA_TYPE => 'forecast',
                $this::PROVIDER => 'OpenWeatherMap',
                $this::FORECAST_DATE => convertDate($parsedJson['list'][$i]['dt']),
                $this::FORECAST_DAYS => ++$dayCounter,
                $this::CITY_ID => $city->getId(),
                $this::TEMPERATURE_HIGH => $parsedJson['list'][$i]['temp']['day'],
                $this::TEMPERATURE_LOW => $parsedJson['list'][$i]['temp']['night'],
                $this::HUMIDITY => $parsedJson['list'][$i]['humidity'],
                $this::PRESSURE => $parsedJson['list'][$i]['pressure']
            ];
        }

        return $forecasts;
    }

    /**
     * Get current weather data
     * @param array $parsedJson
     * @param City $city
     * @return array
     */

    private function getCurrentData(array $parsedJson, City $city): array
    {
        return [
            $this::DATA_TYPE => 'current',
            $this::PROVIDER => 'OpenWeatherMap',
            $this::CURRENT_DATE => new \DateTime('now'),
            $this::CITY_ID => $city->getId(),
            $this::TEMPERATURE_CURRENT => $parsedJson['main']['temp'],
            $this::HUMIDITY => $parsedJson['main']['humidity'],
            $this::PRESSURE => $parsedJson['main']['pressure']
        ];

    }
}
