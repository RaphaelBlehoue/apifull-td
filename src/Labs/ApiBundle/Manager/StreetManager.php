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
use Labs\ApiBundle\Entity\City;
use Labs\ApiBundle\Entity\Street;
use Labs\ApiBundle\DTO\StreetDTO;
use Labs\ApiBundle\Repository\StreetRepository;


/**
 * Class StreetManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.street_manager", public=true)
 *
 */
class StreetManager extends ApiEntityManager
{
    /**
     * @var StreetRepository
     */
    protected $repo;

    /**
     * StreetManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Street');
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


    public function create(City $city, Street $street)
    {
        $street->setCity($city);
        $this->em->persist($city);
        $this->em->flush();
        return $street;
    }


    public function update(Street $street, StreetDTO $dto)
    {
        $street->setName($dto->getName());
        return $street;
    }



    public function findStreetByCity($city, $id)
    {
        $data = $this->repo->getStreetByCity($city, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}