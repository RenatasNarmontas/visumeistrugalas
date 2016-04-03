<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 01/04/16
 * Time: 09:50
 */

namespace AppBundle\Crawler;

interface CrawlerInterface
{
    // Crawls the site
    public function crawl();
}
