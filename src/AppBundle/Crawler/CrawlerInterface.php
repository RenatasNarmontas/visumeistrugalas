<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 01/04/16
 * Time: 09:50
 */

namespace AppBundle\Crawler;

use AppBundle\Entity\City;
use AppBundle\Entity\Provider;

interface CrawlerInterface
{
    /**
     * Crawls the site and returns array of forecast and current temperature for the city
     * @param City $city
     * @return array
     */
    public function crawl(Provider $provider, City $city): array;
}
