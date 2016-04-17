<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 17/04/16
 * Time: 20:05
 */

namespace AppBundle\Persister;

use AppBundle\Entity\Forecast;
use AppBundle\Entity\Temperature;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class WeatherPersister
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * WeatherPersister constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist weather data
     * @param array $temperatures
     */
    public function persist(array $temperatures)
    {
        foreach ($temperatures as $temperature) {
            if (($temperature instanceof Forecast) || ($temperature instanceof Temperature)) {
                $this->entityManager->persist($temperature);
            }
        }
    }

    /**
     * Flush weather data to DB
     */
    public function flush()
    {
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            error_log($e->getMessage());
        }
    }
}
