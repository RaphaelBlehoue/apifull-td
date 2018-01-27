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
use Labs\ApiBundle\Entity\OrderProduct;
use Labs\ApiBundle\Repository\OrderProductRepository;

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

    public function create(){
        //Assignation des elements Du tableau formaté
    }
}