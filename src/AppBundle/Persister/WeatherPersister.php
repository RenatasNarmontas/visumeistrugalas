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
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class WeatherPersister
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * WeatherPersister constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Persist weather data
     * @param array $temperatures
     */
    public function persist(array $temperatures)
    {
        foreach ($temperatures as $temperature) {
            if (($temperature instanceof Forecast) || ($temperature instanceof Temperature)) {
                $entityManager = $this->managerRegistry->getManagerForClass(get_class($temperature));
                $entityManager->persist($temperature);
            }
        }
    }

    /**
     * Flush weather data to DB
     */
    public function flush()
    {
        $entityManager = $this->managerRegistry->getManager();
        try {
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            error_log($e->getMessage());
        }
    }
}
