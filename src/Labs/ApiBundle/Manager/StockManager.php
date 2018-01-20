<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 18/01/2018
 * Time: 16:05
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Entity\Stock;
use Labs\ApiBundle\Repository\StockRepository;


/**
 * Class Stock
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.stock_manager", public=true)
 */

class StockManager extends ApiEntityManager
{


    /**
     * @var StockRepository
     */
    protected $repo;


    /**
     * StockManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em){
        parent::__construct($em);
    }

    /**
     * @return $this
     */
    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Stock');
        return $this;
    }

    /**
     * @return $this
     */
    public function getList()
    {
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    /**
     * @param $product
     * @return $this
     */
    public function getListWithParams($product)
    {
        $this->qb = $this->repo->getListWithParamsQB($product);
        return $this;
    }

    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('stock.'.$column, $direction);
        return $this;
    }

    /**
     * @param Product $product
     * @param Stock $stock
     * @return Stock
     */
    public function create(Product $product, Stock $stock)
    {
        $stock->setProduct($product);
        $this->em->persist($stock);
        $this->em->flush();
        return $stock;
    }

    /**
     * @param $product
     * @param $stock
     * @return bool
     */
    public function findProductByStock($product, $stock)
    {
        $data = $this->repo->getProductByStockId($product, $stock)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }

}