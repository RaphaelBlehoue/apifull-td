<?php

namespace Labs\ApiBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends EntityRepository
{
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('c');
        return $qb;
    }
}
