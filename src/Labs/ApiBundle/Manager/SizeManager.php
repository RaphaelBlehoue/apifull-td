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
use Labs\ApiBundle\Entity\Size;
use Labs\ApiBundle\DTO\SizeDTO;
use Labs\ApiBundle\Repository\SizeRepository;


/**
 * Class SizeManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.size_manager", public=true)
 *
 */
class SizeManager extends ApiEntityManager
{
    /**
     * @var SizeRepository
     */
    protected $repo;

    /**
     * ColorManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Size');
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
        $this->qb->orderBy('s.'.$column, $direction);
        return $this;
    }

    /**
     * @inheritdoc({creation du Color})
     * @param Size $size
     * @return Size
     */
    public function create(Size $size)
    {
        $this->em->persist($size);
        $this->em->flush();
        return $size;
    }

    public function update(Size $size, SizeDTO $dto)
    {
        $size->setSize($dto->getSize());
        return $size;
    }

}