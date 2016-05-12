<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 12/05/16
 * Time: 05:33
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TemperatureRepository extends EntityRepository
{
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

    public function getUniqueDateForGraph(string $city)
    {
        $query = $this->createQueryBuilder('t')
            ->select('date(t.date) as tdate')
            ->where('t.temperature is not null')
            ->distinct()
            ->getQuery();

        return $query->getResult();
    }
}
