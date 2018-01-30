<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 27/01/2018
 * Time: 09:57
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Command;
use Labs\ApiBundle\Entity\OrderProduct;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Repository\OrderProductRepository;

/**
 * Class OrderProductManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.order_product_manager", public=true)
 */
class OrderProductManager extends ApiEntityManager
{

    /**
     * @var OrderProductRepository
     */
    protected $repo;

    /**
     * OrderProductManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    /**
     * @return $this
     */
    protected function setRepository(){
        $this->repo = $this->em->getRepository(OrderProduct::class);
        return $this;
    }

    /**
     * @return $this
     */
    public function getList(){
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('order_product.'.$column, $direction);
        return $this;
    }

    /**
     * @param $quantity
     * @param $price
     * @param OrderProduct $orderProduct
     * @param Product $product
     * @param Command $command
     * @param $promo
     */
    public function create($quantity, $price, OrderProduct $orderProduct ,Product $product, Command $command, $promo){
        $orderProduct->setCommand($command);
        $orderProduct->setProduct($product);
        $orderProduct->setQuantity($quantity);
        $orderProduct->setLinePrice($price->getSellPrice());
        if ($promo === false){
            $orderProduct->setPromoValue(0);
        } else {
            $orderProduct->setPromoValue($promo->getPercent());
        }
        $this->em->persist($orderProduct);
        $this->em->flush();
    }
}