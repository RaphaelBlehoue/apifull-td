<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WarehouseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WarehouseRepository extends EntityRepository
{
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('w');
        return $qb;
    }
}
