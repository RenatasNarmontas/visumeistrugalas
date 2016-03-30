<?php
/**
 * Created by PhpStorm.
 * User: reno
 * Date: 29/03/16
 * Time: 15:03
 */

namespace AppBundle\Crawler;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use AppBundle\Entity\Temperature;
use AppBundle\Entity\Forecast;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CrawlerAbstract extends ContainerAwareCommand implements CrawlerInterface
{
    /**
     * Inserts current values to 'temperatures' table
     */
//    public function insertCurrentTemperature(TemperatureEntity temperature)
//    {
//        $em = $this->getContainer()->get('doctrine')->getManager();
//        $em->persist($forecasts_1d);
//        $em->flush();
//    }

    public function configure()
    {
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * Inserts forecast values to 'forecasts' table
     */
    public function insertForecastTemperature(Forecast $forecast)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecast);
        $em->flush();
    }
}
