<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PersonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends EntityRepository
{
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('p');
        return $qb;
    }
}
