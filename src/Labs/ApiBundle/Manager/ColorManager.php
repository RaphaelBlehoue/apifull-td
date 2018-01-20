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
use Labs\ApiBundle\Entity\Color;
use Labs\ApiBundle\DTO\ColorDTO;
use Labs\ApiBundle\Repository\ColorRepository;


/**
 * Class ColorManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.color_manager", public=true)
 *
 */

class ColorManager extends ApiEntityManager
{
    /**
     * @var ColorRepository
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Color');
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
        $this->qb->orderBy('c.'.$column, $direction);
        return $this;
    }

    /**
     * @param $column
     * @param array $tabs
     * @return array
     */
    public function InArray($column, array $tabs){
        $this->qb->where($this->qb->expr()->in('c.'.$column, $tabs));
        return $this->getAll();
    }

    /**
     * @inheritdoc({creation du Color})
     * @param Color $color
     * @return Color
     */
    public function create(Color $color)
    {
        $this->em->persist($color);
        $this->em->flush();
        return $color;
    }

    public function update(Color $color, ColorDTO $dto)
    {
        $color->setColor($dto->getColor());
        return $color;
    }


    public function where($options)
    {
        // TODO: Implement where() method.
    }
}