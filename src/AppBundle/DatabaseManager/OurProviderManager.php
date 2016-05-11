<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 06/05/16
 * Time: 21:27
 */

namespace AppBundle\DatabaseManager;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Provider;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Class OurProviderManager
 * @package AppBundle\DatabaseManager
 */
class OurProviderManager extends DatabaseManagerAbstract
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * OurProviderManager constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param string|null $startDate
     * @param string|null $endDate
     * @throws WeatherProviderException
     */
    public function addOurProviderForecast(string $startDate = null, string $endDate = null)
    {
        $entityManager = $this->managerRegistry->getManager();
        
        // Get average forecast values of other providers
        $forecastAverage = $entityManager
            ->getRepository('AppBundle:Forecast')
            ->getForecastAverage($startDate, $endDate);

        if (0 != count($forecastAverage)) {
            // Get our provider
            /** @var Provider $ourProvider */
            $ourProvider = $entityManager->getRepository('AppBundle:Provider')->findOneByName('Advanced Weather');
            if (null === $ourProvider) {
                new WeatherProviderException('Can\'t get "Advanced Weather" provider from DB.');
            }

            foreach ($forecastAverage as $forecastItem) {
                // Create Forecast object
                $forecast = $this->makeForecastObject(
                    $entityManager,
                    $ourProvider->getId(),
                    $forecastItem['forecastDate']->format('Y-m-d'),
                    $forecastItem['forecastDays'],
                    $forecastItem['cityId'],
                    $forecastItem['avgTempHigh'],
                    $forecastItem['avgTempLow'],
                    null, //temperatureHighDeviation,
                    null, //$temperatureLowDeviation,
                    null, //$humidity,
                    null, //$humidityDeviation,
                    null, //$pressure,
                    null  //$pressureDeviation
                );

                // Persist Forecast object
                $entityManager->persist($forecast);
            }
            // Flush objects
            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $ucve) {
                // Already in database. Ignore
            }
        } else {
            new WeatherProviderException('Can\'t get forecast data for other providers from DB.');
        }
    }
}
