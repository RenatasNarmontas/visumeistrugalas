<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherCrawlerCommand extends ContainerAwareCommand
{
    /**
     * Configure Command
     */
    public function configure()
    {
        $this
            ->setName('crawler:weather')
            ->setDescription('Import forecast and current data by cities')
            ->setHelp(<<<EOF
The <info>crawler:weather</info> command imports weather data to DB from all weather providers.

<comment>Samples:</comment>
    To get weather information and save it to DB:
    <info>php app/console crawler:weather</info>
EOF
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws WeatherProviderException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Log to app/logs
        $logger = $this->getContainer()->get('logger');

        // Wunderground service
        $wundergroundCrawler = $this->getContainer()->get('wunderground.crawler.service');
        // TODO: placeholder for OpenWeatherMap service
        //$openWeatherMapCrawler = $this->getContainer()->get('openweathermap.crawler.service');

        // Yahoo service
        $yahooCrawler = $this->getContainer()->get('yahoo.crawler.service');
        $databaseManagerService = $this->getContainer()->get('weather.manager.service');

        // Fetch cities
        $cities = $databaseManagerService->fetchCities();

        // Cycle through each city/country
        foreach ($cities as $city) {
            /** @var $city City */
            $logger->info('Fetching weather data for '.$city->getName().'/'.$city->getCountry());

            // Wunderground crawler
            try {
                $temperatures = $wundergroundCrawler->crawl($city);
                $databaseManagerService->persist($temperatures);
            } catch (WeatherProviderException $e) {
                $logger->error($e->getMessage());
            }
            unset($temperatures);

            // TODO: placeholder for OpenWeatherMap crawler
            // OpenWeatherMap crawler
            //$temperatures = $openWeatherMapCrawler->crawl($city);
            //$databaseManagerService->persist($temperatures);
            //unset($temperatures);

            // Yahoo crawler
            try {
                $temperatures = $yahooCrawler->crawl($city);
                $databaseManagerService->persist($temperatures);
            } catch (WeatherProviderException $e) {
                $logger->error($e->getMessage());
            }
            unset($temperatures);
        }

        // Flush weather data to DB
        $databaseManagerService->flush();

        $logger->info('weather crawler finish');

        return 0;
    }
}
