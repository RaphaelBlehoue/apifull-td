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
use Labs\ApiBundle\DTO\BrandDTO;
use Labs\ApiBundle\Repository\BrandRepository;


/**
 * Class BrandManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.brand_manager", public=true)
 *
 */
class BrandManager extends ApiEntityManager
{
    /**
     * @var BrandRepository
     */
    protected $repo;

    /**
     * BrandRepository constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }


    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Brand');
        return $this;
    }

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
        $this->qb->orderBy('b.'.$column, $direction);
        return $this;
    }

    /**
     * @param Brand $brand
     * @return Brand
     * @inheritdoc({creation du Brand})
     */
    public function create(Brand $brand)
    {
        $this->em->persist($brand);
        $this->em->flush();
        return $brand;
    }


    public function update(Brand $brand, BrandDTO $dto)
    {
        $brand->setName($dto->getName());
        return $brand;
    }
}