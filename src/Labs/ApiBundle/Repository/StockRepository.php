<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StockRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StockRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('stock');
        return $qb;
    }

    /**
     * @param $product
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListWithParamsQB($product)
    {
        $qb = $this->createQueryBuilder('stock');
        $qb->where($qb->expr()->eq('stock.product', ':product'));
        $qb->setParameter('product', $product);
        return $qb;
    }

    /**
     * @param $product
     * @param $stock
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getProductByStockId($product, $stock)
    {
        $qb = $this->createQueryBuilder('stock');
        $qb->where('stock.id = :stock');
        $qb->andWhere('stock.product = :product');
        $qb->setParameter('stock', $stock);
        $qb->setParameter('product', $product);
        return $qb;
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getLastStockLineBeforeNewPersist($productId)
    {
        $qb = $this->createQueryBuilder('stock');
        $qb->where('stock.product = :productId');
        $qb->orderBy('stock.created', 'DESC');
        $qb->setParameter('productId', $productId);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
