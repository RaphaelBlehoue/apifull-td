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
use Labs\ApiBundle\DTO\PromotionDTO;
use Labs\ApiBundle\Entity\Promotion;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Repository\PromotionRepository;


/**
 * Class PromotionManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.promotion_manager", public=true)
 */

class PromotionManager extends ApiEntityManager
{

    /**
     * @var PromotionRepository
     */
    protected $repo;

    /**
     * PromotionManager constructor.
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
        $this->repo = $this->em->getRepository(Promotion::class);
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
        $this->qb->orderBy('promo.'.$column, $direction);
        return $this;
    }

    /**
     * @param Product $product
     * @param Promotion $promotion
     * @return Promotion
     */
    public function create(Product $product, Promotion $promotion)
    {
        $promotion->setProduct($product);
        $this->em->persist($promotion);
        $this->em->flush();
        return $promotion;
    }

    /**
     * @param Promotion $promotion
     * @param PromotionDTO $dto
     * @return Promotion
     */
    public function update(Promotion $promotion, PromotionDTO $dto)
    {
        $promotion
            ->setName($dto->getName())
            ->setContent($dto->getContent())
            ->setPercent($dto->getPercent());
        return $promotion;
    }

    /**
     * @param Promotion $promotion
     * @param $fieldName
     * @param $fieldValue
     * @param $product
     * @return Promotion
     */
    public function patch(Promotion $promotion, $fieldName, $fieldValue, $product)
    {
        if ($fieldName == 'active' && $fieldValue !== false) {
            $this->patchStatusPromo($product);
            $promotion->setActived($fieldValue);
        }else {
            $promotion->setActived($fieldValue);
        }
        $this->em->merge($promotion);
        $this->em->flush();
        return $promotion;
    }

    /**
     * @param $product
     * @return bool
     * Desactived all Promotions
     */
    public function patchStatusPromo($product){
        $allProductPromo = $this->findAllPromoByProduct($product);
        foreach ($allProductPromo as $k => $line) {
            $line->setActived(false);
            $this->em->merge($line);
        }
        $this->em->flush();
        return true;
    }

    /**
     * @param $product
     * @param $promotion
     * @return bool
     */
    public function findPromoByproduct($product, $promotion){
         $data = $this->repo->getPromoByProductId($product, $promotion)->getQuery()->getOneOrNullResult();
         if ($data === null){ return false;}
         return true;
    }

    /**
     * @param $product
     * @return mixed
     */
    public function findAllPromoByProduct($product){
        return $this->repo->getAllPromotionByProductId($product)->getQuery()->getResult();
    }
}
