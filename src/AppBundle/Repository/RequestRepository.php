<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 02/05/16
 * Time: 03:48
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class RequestRepository
 * @package AppBundle\Repository
 */
class RequestRepository extends EntityRepository
{
    /**
     * @param int $count
     * @return array
     */
    public function findTopXCitiesOrderedByCount(int $count)
    {
        $query = $this->createQueryBuilder('r')
            ->select('r.city, COUNT(r.city) as request_count')
            ->addGroupBy('r.city')
            ->addOrderBy('request_count', 'DESC')
            ->setMaxResults($count)
            ->getQuery();

        return $query->getResult();
    }
}
