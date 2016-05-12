<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 12/05/16
 * Time: 05:33
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TemperatureRepository
 * @package AppBundle\Repository
 */
class TemperatureRepository extends EntityRepository
{
    /**
     * Get average temperature for city for provider
     * @param string $city
     * @param string $provider
     * @return array
     */
    public function getAvgTempDataForGraph(string $city, string $provider)
    {
        $query = $this->createQueryBuilder('t')
            ->select('(t.provider) as provider_id, date(t.date) as tdate, round(avg(t.temperature), 2) as avg_temp')
            ->innerJoin('t.city', 'c')
            ->innerJoin('t.provider', 'p')
            ->where('c.name=:city')
            ->setParameter('city', $city)
            ->andWhere('p.name=:provider')
            ->setParameter('provider', $provider)
            ->andWhere('t.temperature is not null')
            ->groupBy('provider_id, tdate')
            ->orderBy('tdate')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Get temperature dates for city
     * @param string $city
     * @return array
     */
    public function getUniqueDateForGraph(string $city)
    {
        $query = $this->createQueryBuilder('t')
            ->select('date(t.date) as tdate')
            ->innerJoin('t.city', 'c')
            ->where('c.name=:city')
            ->setParameter('city', $city)
            ->andWhere('t.temperature is not null')
            ->distinct()
            ->getQuery();

        return $query->getResult();
    }
}
