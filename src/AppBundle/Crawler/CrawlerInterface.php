<?php

namespace AppBundle\Crawler;

use AppBundle\Entity\Forecasts_1d;
use AppBundle\Entity\Forecasts_2d;
use AppBundle\Entity\Forecasts_3d;
use AppBundle\Entity\Forecasts_4d;
use AppBundle\Entity\Forecasts_5d;
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
    public function insertForecast1dTemperature(Forecasts_1d $forecast_1d);
    public function insertForecast2dTemperature(Forecasts_2d $forecast_2d);
    public function insertForecast3dTemperature(Forecasts_3d $forecast_3d);
    public function insertForecast4dTemperature(Forecasts_4d $forecast_4d);
    public function insertForecast5dTemperature(Forecasts_5d $forecast_5d);
}
