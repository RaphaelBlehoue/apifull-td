<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SectionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SectionRepository extends EntityRepository
{

    public function getListQB()
    {
        $qb = $this->createQueryBuilder('q');
        return $qb;
    }

    public function getOneSectionCategory($category, $section)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.category', 's');
        $qb->addSelect('c');
        $qb->where(
            $qb->expr()->eq('s.id', ':section'),
            $qb->expr()->eq('c.id',':category')
        );
        $qb->setParameter('category', $category);
        $qb->setParameter('section', $section);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
