<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyRepository extends EntityRepository
{
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('c');
        return $qb;
    }
}
