<?php
/**
 * Created by PhpStorm.
 * User: reno
 * Date: 29/03/16
 * Time: 15:03
 */

namespace AppBundle\Crawler;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use AppBundle\Entity\Temperatures;
use AppBundle\Entity\Forecasts_1d;
use AppBundle\Entity\Forecasts_2d;
use AppBundle\Entity\Forecasts_3d;
use AppBundle\Entity\Forecasts_4d;
use AppBundle\Entity\Forecasts_5d;
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
     * Inserts forecast values to 'forecasts_1d' table
     */
    public function insertForecast1dTemperature(Forecasts_1d $forecasts_1d)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecasts_1d);
        $em->flush();
    }

    /**
     * Inserts forecast values to 'forecasts_2d' table
     */
    public function insertForecast2dTemperature(Forecasts_2d $forecasts_2d)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecasts_2d);
        $em->flush();
    }

    /**
     * Inserts forecast values to 'forecasts_3d' table
     */
    public function insertForecast3dTemperature(Forecasts_3d $forecasts_3d)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecasts_3d);
        $em->flush();
    }

    /**
     * Inserts forecast values to 'forecasts_4d' table
     */
    public function insertForecast4dTemperature(Forecasts_4d $forecasts_4d)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecasts_4d);
        $em->flush();
    }

    /**
     * Inserts forecast values to 'forecasts_5d' table
     */
    public function insertForecast5dTemperature(Forecasts_5d $forecasts_5d)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($forecasts_5d);
        $em->flush();
    }

    /**
     * Logger
     *
     * @param string $message
     */
    abstract protected function logger(string $message);
}
