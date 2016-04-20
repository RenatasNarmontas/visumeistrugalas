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

/**
 * Class WundergroundCrawlerCommand
 * @package AppBundle\Command
 * @deprecated
 */
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
        $persistService = $this->getContainer()->get('weather.manager.service');

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
}
