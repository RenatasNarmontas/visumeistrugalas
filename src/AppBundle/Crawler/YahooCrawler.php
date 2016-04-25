<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 25/04/16
 * Time: 17:11
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;

class YahooCrawler extends CrawlerAbstract
{

    const BASE_URL = 'https://query.yahooapis.com/v1/public/yql?q=';
    const YQL_QUERY = 'select * from weather.forecast where woeid in '
                    .'(select woeid from geo.places where text="%s, %s") and u="C"';
    const TAIL_URL = '&format=json';
    
    /**
     * Crawls the site and returns array of forecast and current temperature for the city
     * @param City $city
     * @return array
     * @throws WeatherProviderException
     */
    public function crawl(City $city): array
    {
        $url = $this->getUrl($city);

        // Get content
        $jsonString = file_get_contents($url);

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

        if (isset($parsedJson->query->count) && (0 === $parsedJson->query->count)) {
            throw new WeatherProviderException('JSON response error. No data');
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
     * Construct url
     * @param City $city
     * @return string
     */
    private function getUrl(City $city): string
    {
        return $this::BASE_URL
            .urlencode(
                sprintf(
                    $this::YQL_QUERY,
                    $this->normalizeCityName($city->getName()),
                    $city->getCountry()
                )
            )
            .$this::TAIL_URL;
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

        $dt = new \DateTime('now');

        if (!isset($parsedJson->query->results->channel->item->forecast)) {
            return $result;
        }

        $i = 0;
        foreach ($parsedJson->query->results->channel->item->forecast as $forecastItem) {
            // Skip forecast for the current day
            if (0 === $i) {
                $i++;
                continue;
            }

            // Get forecast conditions
            $forecastArray = [
                $this::DATA_TYPE => 'forecast',
                $this::PROVIDER => 'yahoo',
                $this::FORECAST_DATE => $dt,
                $this::FORECAST_DAYS => $i,
                $this::CITY_ID => $city->getId(),
                $this::TEMPERATURE_HIGH => $forecastItem->high,
                $this::TEMPERATURE_LOW => $forecastItem->low,
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
            $this::PROVIDER => 'yahoo',
            $this::CURRENT_DATE => new \DateTime('now'),
            $this::CITY_ID => $city->getId(),
            $this::TEMPERATURE_CURRENT => $parsedJson->query->results->channel->item->condition->temp
        ];
    }
}
