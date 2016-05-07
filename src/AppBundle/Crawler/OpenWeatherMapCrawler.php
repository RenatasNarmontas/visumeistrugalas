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
        $uri = 'http://api.openweathermap.org/data/2.5/forecast/daily?q='
               .$city->getName().'&mode=json&units=metric&cnt=5&appid='.$this->apiKey;
        $data = file_get_contents($uri);


    }

    private function parseData($data)
    {
        if (isset($data->cod) && $data->cod !== 200) {
            throw new WeatherProviderException('The request was unsuccessful');
        }

    }
}
