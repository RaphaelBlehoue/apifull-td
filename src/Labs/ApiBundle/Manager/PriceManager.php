<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/01/2018
 * Time: 13:40
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\DTO\PriceDTO;
use Labs\ApiBundle\Entity\Price;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Repository\PriceRepository;


/**
 * Class PriceManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.price_manager", public=true)
 */

class PriceManager extends ApiEntityManager
{

    /**
     * @var PriceRepository
     */
    protected $repo;

    /**
     * PriceManager constructor.
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
    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Price');
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
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('price.'.$column, $direction);
        return $this;
    }

    /**
     * @param Product $product
     * @param Price $price
     * @return Price
     */
    public function create(Product $product, Price $price)
    {
        $price->setProduct($product);
        $this->em->persist($price);
        $this->em->flush();
        return $price;
    }

    /***
     * @param Price $price
     * @param PriceDTO $dto
     * @return Price
     */
    public function update(Price $price, PriceDTO $dto)
    {
        $price
            ->setBuyPrice($dto->getBuyPrice())
            ->setSellPrice($dto->getSellPrice())
            ->setNegociteLimitPrice($dto->getNegociteLimitPrice());
        return $price;
    }

    /**
     * @param Price $price
     * @param $fieldName
     * @param $fieldValue
     * @return Price
     */
    public function patch(Price $price, $fieldName, $fieldValue)
    {
        if ($fieldName == 'active') {
            $price->setNegociate($fieldValue);
        }
        $this->em->merge($price);
        $this->em->flush();
        return $price;
    }

    /**
     * @param $product
     * @param $price
     * @return bool
     */
    public function findPriceByproduct($product, $price)
    {
        $data = $this->repo->getPriceByproductId($product, $price)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }

    public function where($options)
    {
        // TODO: Implement where() method.
    }
}