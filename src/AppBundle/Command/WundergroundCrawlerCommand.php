<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

use AppBundle\Crawler\CrawlerAbstract;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
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

<comment>Samples:</comment>
    To get weather information for Vilnius/Lithuania:
    <info>php app/console crawler:wunderground --city=Vilnius --country=Lithuania</info>
EOF
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Get city and country parameters
        $this->output = $output;
        $cityName = $this->normalizeCityName($input->getOption('city'));
        $country = $input->getOption('country');

        // Read wunderground API key from config
        $wuApi = $this->getContainer()->getParameter('wundergroung_api');

        // Check API key
        if (null === $wuApi) {
            $output->writeln('Wunderground API key is missing');
            return;
        }

        // Get content
        $json_string = file_get_contents(
            sprintf(
                'http://api.wunderground.com/api/%s/forecast10day/conditions/q/%s/%s.json',
                $wuApi,
                $country,
                $cityName
            )
        );

        // Parse json
        $parsed_json = json_decode($json_string);

        // Insert to DB
        $i = 0;
        foreach ($parsed_json->forecast->simpleforecast->forecastday as $forecastItem) {
            $forecastObject = $this->makeForecastObject($forecastItem, $i);
            parent::insertForecastTemperature($forecastObject);
            $i++;
            if (5 === $i) {
                return;
            }
        }
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
     * @return Forecast
     */
    private function makeForecastObject($forecastItem, $i)
    {
        // Get provider from DB by name
        $em = $this->getContainer()->get('doctrine')->getManager();
        $provider = $em->getRepository('AppBundle:Provider')->findOneBy(array('name' => 'wunderground'));

        $forecast = new Forecast();
        $forecast->setProvider($provider);
        // Convert UNIX timestamp to PHP DateTime
        $epoch = $forecastItem->date->epoch;
        $dt = new \DateTime();
        $dt->setTimestamp($epoch);
        $forecast->setForecastDate($dt);
        $forecast->setForecastDays($i + 1);
        $forecast->setCityId(1);
        $forecast->setTemperatureHigh($forecastItem->high->celsius);
        $forecast->setTemperatureLow($forecastItem->low->celsius);
        $forecast->setHumidity($forecastItem->avehumidity);

        return $forecast;
    }
}
