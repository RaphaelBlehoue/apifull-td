<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/12/2017
 * Time: 20:32
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Brand;
use Labs\ApiBundle\Entity\Price;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\DTO\ProductDTO;
use Labs\ApiBundle\Entity\Promotion;
use Labs\ApiBundle\Entity\Section;
use Labs\ApiBundle\Entity\Store;
use Labs\ApiBundle\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * Class ProductManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.product_manager", public=true)
 *
 */

class ProductManager extends ApiEntityManager
{
    /**
     * @var ProductRepository
     */
    protected $repo;
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $em
     * @param RegistryInterface $registry
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "registry" = @DI\Inject("doctrine")
     * })
     */
    public function __construct(EntityManagerInterface $em, RegistryInterface $registry)
    {
        parent::__construct($em);
        $this->registry = $registry;
    }

    /**
     * @return $this
     */
    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Product');
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
        $this->qb->orderBy('p.'.$column, $direction);
        return $this;
    }

    /**
     * @inheritdoc({creation du Product})
     * @param Product $product
     * @param Section $section
     * @param Store $store
     * @return Product
     */
    public function create(Product $product, Section $section, Store $store): Product
    {
        $product->__construct();
        $product->setSection($section);
        $product->setStore($store);
        $this->em->persist($product);
        $this->em->flush();
        return $product;
    }

    /**
     * @param Product $product
     * @param ProductDTO $dto
     * @return Product
     */
    public function update(Product $product, ProductDTO $dto): Product
    {
        $product->setName($dto->getName())
            ->setLength($dto->getLength())
            ->setWeight($dto->getWeight())
            ->setPound($dto->getPound())
            ->setUnit($dto->getUnit());
        return $product;
    }

    /**
     * @param Brand $brand
     * @param Product $product
     * @return Product
     */
    public function patchProductBrand(Brand $brand, Product $product): Product
    {
        $product->setBrand($brand);
        $this->em->merge($product);
        $this->em->flush();
        return $product;
    }

    public function getProductBySku($sku){
        $data = $this->repo->findProductBySku($sku)->getQuery()->getOneOrNullResult();
        return $data;
    }

    /**
     * @param $product
     * @return bool|mixed
     */
    public function getActivedPromotion($product){
        $promotion = $this->registry->getRepository(Promotion::class)->getPromotionActivedForProductId($product);
        return ($promotion !== null) ? $promotion : false;
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getActivedPrice($product){
        $price = $this->registry->getRepository(Price::class)->getPriceActivedForProductId($product);
        return $price;
    }

    /**
     * @param Product $product
     * @param $fieldName
     * @param $fieldValue
     * @return Product
     */
    public function patchProductStatus(Product $product, $fieldName, $fieldValue){
        if ($fieldName == 'status') {
            $product->setStatus($fieldValue);
        }
        $this->em->merge($product);
        $this->em->flush();
        return $product;
    }

    /**
     * @param $section
     * @param $store
     * @param $id
     * @return bool
     */
    public function findSectionStoreByProduct($section,$store, $id): bool
    {
        $data = $this->repo->getSectionStoreByProduct($section, $store, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }

}