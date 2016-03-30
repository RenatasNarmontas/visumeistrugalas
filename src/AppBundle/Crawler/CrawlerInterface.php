<?php

namespace AppBundle\Crawler;

use AppBundle\Entity\Forecast;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CrawlerInterface
{
    // Console functions
    public function configure();
    public function execute(InputInterface $inputInterface, OutputInterface $outputInterface);

    // Current temperature DB functions
    //public function insertCurrentTemperature(Temperatures $temperature, OutputInterface $output);

    // Forecast DB functions
    public function insertForecastTemperature(Forecast $forecast);
}
