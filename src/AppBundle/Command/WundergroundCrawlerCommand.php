<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\Provider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WundergroundCrawlerCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Provider
     */
    private $provider;

    public function configure()
    {
        $this
            ->setName('crawler:wunderground')
            ->setDescription('Import data forecast by cities from api.wunderground.com')
            ->setHelp(<<<EOF
The <info>crawler:wunderground</info> command imports weather data to DB from wunderground.com.

<comment>Samples:</comment>
    To get weather information and save it to DB:
    <info>php app/console crawler:wunderground</info>
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
        $this->logIt($output, 'wunderground crawler start');

        $this->entityManager = $this->getContainer()->get('doctrine');
        $wundergroundCrawler = $this->getContainer()->get('wunderground.crawler.service');
        $persistService = $this->getContainer()->get('weather.persister.service');

        // Fetch cities and countries
        $cities = $this->fetchCities();
        // Fetch provider
        $this->provider = $this->fetchProvider();

        // Cycle through each city/country
        foreach ($cities as $city) {
            $temperatures = $wundergroundCrawler->crawl($this->provider, $city);
            $persistService->persist($temperatures);
        }
        $persistService->flush();

        $this->logIt($output, 'wunderground crawler finish');

        return 0;
    }

    /**
     * Helper function to log messages
     * @param OutputInterface $output
     * @param string $message
     */
    private function logIt(OutputInterface $output, string $message)
    {
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln(date('Y-m-d H:i:s').' - '.$message);
        }
    }

    /**
     * Get all cities and countries from DB
     * @return array
     */
    private function fetchCities()
    {
        $cities = $this->entityManager->getRepository('AppBundle:City')->findAll();
        return $cities;
    }

    /**
     * Get provider
     *
     * @return Provider
     * @throws WeatherProviderException
     */
    private function fetchProvider()
    {
        $provider = $this->entityManager->getRepository('AppBundle:Provider')->findOneBy(array('name' => 'wunderground'));
        if (!($provider instanceof Provider)) {
            throw new WeatherProviderException('Can\'t get wunderground provider from DB');
        }

        return $provider;
    }
}
