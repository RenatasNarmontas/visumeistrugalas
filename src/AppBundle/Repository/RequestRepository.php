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
    public function findTopXOrderedByCount(int $count)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        return $queryBuilder->select('r.city, COUNT(r.city) as request_count')
            ->addGroupBy('r.city')
            ->addOrderBy('request_count', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute();
    }
}
