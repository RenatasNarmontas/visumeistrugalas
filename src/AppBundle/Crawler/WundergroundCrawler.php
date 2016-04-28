<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 01/04/16
 * Time: 22:20
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;

class WundergroundCrawler extends CrawlerAbstract
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
     * Crawl wunderground site
     * @param City $city
     * @return array
     * @throws WeatherProviderException
     */
    public function crawl(City $city): array
    {
        // Get content
        $jsonString = file_get_contents(
            sprintf(
                'http://api.wunderground.com/api/%s/forecast10day/conditions/q/%s/%s.json',
                $this->wundergroundApiKey,
                $city->getCountry(),
                $this->normalizeCityName($city->getName())
            )
        );

        if ($jsonString === false) {
            $error = error_get_last();
            throw new WeatherProviderException(
                sprintf(
                    'HTTP request error: %s',
                    $error['message']
                )
            );
        }

        // Decode json
        $parsedJson = json_decode($jsonString);

        if (isset($parsedJson->response->error->type)) {
            throw new WeatherProviderException(
                sprintf(
                    'JSON response error: %s - %s',
                    $parsedJson->response->error->type,
                    $parsedJson->response->error->description
                )
            );
        }

        // Get forecast data
        $forecastArray = $this->getForecastData($parsedJson, $city);
        $result = $forecastArray;

        // Get current data
        $currentArray = $this->getCurrentData($parsedJson, $city);
        array_push($result, $currentArray);

        return $result;
    }

    /**
     * Get weather forecast data
     * @param \stdClass $parsedJson
     * @param City $city
     * @return array
     */
    private function getForecastData(\stdClass $parsedJson, City $city): array
    {
        // Array of forecasts
        $result = array();

        // Convert forecast date from UNIX timestamp to PHP DateTime
        $epoch = $parsedJson->forecast->simpleforecast->forecastday[0]->date->epoch;
        $dt = new \DateTime();
        $dt->setTimestamp($epoch);

        $i = 0;
        foreach ($parsedJson->forecast->simpleforecast->forecastday as $forecastItem) {
            // Skip forecast for the current day
            if (0 === $i) {
                $i++;
                continue;
            }

            // Get forecast conditions
            $forecastArray = [
                $this::DATA_TYPE => 'forecast',
                $this::PROVIDER => 'Weather Underground',
                $this::FORECAST_DATE => $dt,
                $this::FORECAST_DAYS => $i,
                $this::CITY_ID => $city->getId(),
                $this::TEMPERATURE_HIGH => $forecastItem->high->celsius,
                $this::TEMPERATURE_LOW => $forecastItem->low->celsius,
                $this::HUMIDITY => $forecastItem->avehumidity
            ];

            array_push($result, $forecastArray);
            unset($forecastArray);

            // We need 5 days forecast only
            if (5 === $i) {
                break;
            }
            $i++;
        }

        return $result;
    }

    /**
     * Get current weather data
     * @param \stdClass $parsedJson
     * @param City $city
     * @return array
     */
    private function getCurrentData(\stdClass $parsedJson, City $city): array
    {
        // Get current conditions
        return [
            $this::DATA_TYPE => 'current',
            $this::PROVIDER => 'Weather Underground',
            $this::CURRENT_DATE => new \DateTime('now'),
            $this::CITY_ID => $city->getId(),
            $this::TEMPERATURE_CURRENT => $parsedJson->current_observation->temp_c,
            $this::HUMIDITY => $parsedJson->current_observation->relative_humidity,
            $this::PRESSURE => $parsedJson->current_observation->pressure_mb
        ];
    }
}
