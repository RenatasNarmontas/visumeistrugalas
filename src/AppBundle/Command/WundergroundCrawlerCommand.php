<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Crawler\CrawlerAbstract;
use AppBundle\Entity\Forecasts_1d;
use AppBundle\Entity\Forecasts_2d;
use AppBundle\Entity\Forecasts_3d;
use AppBundle\Entity\Forecasts_4d;
use AppBundle\Entity\Forecasts_5d;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WundergroundCrawlerCommand extends CrawlerAbstract
{
    private $output;

    public function configure()
    {
        $this
            ->setName('crawler:wunderground')
            ->setDescription('Import data forecast by cities from api.wunderground.com')
            ->addOption(
                'city',
                null,
                InputOption::VALUE_REQUIRED,
                'City name'
            )
            ->addOption(
                'country',
                null,
                InputOption::VALUE_REQUIRED,
                'Country'
            )
            ->setHelp(<<<EOF
The <info>crawler:wunderground</info> command imports weather data to DB from wunderground.com.

<info>php app/console crawler:wunderground --city=Vilnius --country=Lithuania</info>

EOF
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $cityName = $this->normalizeCityName($input->getOption('city'));
        $country = $input->getOption('country');
        $wuApi = $this->getContainer()->getParameter('wundergroung_api');

        $json_string = file_get_contents(
            'http://api.wunderground.com/api/'
            .$wuApi
            .'/forecast10day/conditions/q/'
            .$country
            .'/'
            .$cityName
            .'.json'
        );
        $parsed_json = json_decode($json_string);

        $i = 0;
        foreach ($parsed_json->forecast->simpleforecast->forecastday as $forecastItem) {
            $forecastObject = $this->makeForecastObject($forecastItem, $i);
            $i++;

            switch (get_class($forecastObject)) {
                case 'AppBundle\Entity\Forecasts_1d':
                    $this->logger('Inserting to forecast_1d');
                    parent::insertForecast1dTemperature($forecastObject);
                    break;
                case 'AppBundle\Entity\Forecasts_2d':
                    if ($output->isVerbose()) {
                        $output->writeln('Inserting to forecast_2d');
                    }
                    parent::insertForecast2dTemperature($forecastObject);
                    break;
                case 'AppBundle\Entity\Forecasts_3d':
                    if ($output->isVerbose()) {
                        $output->writeln('Inserting to forecast_3d');
                    }
                    parent::insertForecast3dTemperature($forecastObject);
                    break;
                case 'AppBundle\Entity\Forecasts_4d':
                    if ($output->isVerbose()) {
                        $output->writeln('Inserting to forecast_4d');
                    }
                    parent::insertForecast4dTemperature($forecastObject);
                    break;
                case 'AppBundle\Entity\Forecasts_5d':
                    if ($output->isVerbose()) {
                        $output->writeln('Inserting to forecast_5d');
                    }
                    parent::insertForecast5dTemperature($forecastObject);
                    break;
                default:
                    return;
            }
        }

//        $forecasts_1d = new Forecasts_1d();
//        $forecasts_1d->setProviderId(1);
//        $epoch = $parsed_json->forecast->simpleforecast->forecastday[0]->date->epoch;
//        $dt = new \DateTime();      // Convert UNIX timestamp to PHP DateTime
//        $dt->setTimestamp($epoch);
//        $forecasts_1d->setForecastDate($dt);
//        $forecasts_1d->setCityId(1);
//        $forecasts_1d->setTemperatureHigh($parsed_json->forecast->simpleforecast->forecastday[0]->high->celsius);
//        $forecasts_1d->setTemperatureLow($parsed_json->forecast->simpleforecast->forecastday[0]->low->celsius);
//        $forecasts_1d->setHumidity($parsed_json->forecast->simpleforecast->forecastday[0]->avehumidity);
//
//        parent::insertForecast1dTemperature($forecasts_1d, $output);

//
//        $forecasts_1d->setForecastDate($parsed_json->{'forecast'}->{'simpleforecast'}->{'forecastday'})
//
//        $city = $parsed_json->{'location'}->{'city'};
//        $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
//        $fullLocation = $parsed_json->{'current_observation'}->{'display_location'}->{'full'};
//
//        $output->writeln('City: '.$city);
//        $output->writeln('Temperature: '.$temp_c);
//        $output->writeln('Full location: '.$fullLocation);
    }

    /**
     * Normalizes city name
     *
     * @param string $cityName City name for normalization
     * @return string
     */
    private function normalizeCityName(string $cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }

    /**
     * Forecast object factory
     *
     * @param $forecastItem
     * @param int $i
     * @return Forecasts_1d|Forecasts_2d|Forecasts_3d|Forecasts_4d|Forecasts_5d
     */
    private function makeForecastObject($forecastItem, $i)
    {
        switch ($i) {
            case 0:
                $forecast = new Forecasts_1d();
                break;
            case 1:
                $forecast = new Forecasts_2d();
                break;
            case 2:
                $forecast = new Forecasts_3d();
                break;
            case 3:
                $forecast = new Forecasts_4d();
                break;
            case 4:
                $forecast = new Forecasts_5d();
                break;
            default:
                return;
        }

        $forecast->setProviderId(1);
        // Convert UNIX timestamp to PHP DateTime
        $epoch = $forecastItem->date->epoch;
        $dt = new \DateTime();
        $dt->setTimestamp($epoch);
        $forecast->setForecastDate($dt);
        $forecast->setCityId(1);
        $forecast->setTemperatureHigh($forecastItem->high->celsius);
        $forecast->setTemperatureLow($forecastItem->low->celsius);
        $forecast->setHumidity($forecastItem->avehumidity);

        return $forecast;
    }

    protected function logger(string $message)
    {
        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->writeln($message);
        }
    }
}
