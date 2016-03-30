<?php

namespace AppBundle\Crawler;

use AppBundle\Entity\Forecasts1d;
use AppBundle\Entity\Forecasts2d;
use AppBundle\Entity\Forecasts3d;
use AppBundle\Entity\Forecasts4d;
use AppBundle\Entity\Forecasts5d;
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
    public function insertForecast1dTemperature(Forecasts1d $forecast1d);
    public function insertForecast2dTemperature(Forecasts2d $forecast2d);
    public function insertForecast3dTemperature(Forecasts3d $forecast3d);
    public function insertForecast4dTemperature(Forecasts4d $forecast4d);
    public function insertForecast5dTemperature(Forecasts5d $forecast5d);
}
