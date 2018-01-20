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
use Labs\ApiBundle\DTO\CityDTO;
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\Repository\CityRepository;


/**
 * Class CityManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.city_manager", public=true)
 *
 */

class CityManager extends ApiEntityManager
{
    /**
     * @var CityRepository
     */
    protected $repo;

    /**
     * CityManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:City');
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
     * @param Country $country
     * @param City $city
     * @return City
     */
    public function create(Country $country, City $city)
    {
        $city->setCountry($country);
        $this->em->persist($city);
        $this->em->flush();
        return $city;
    }

    /**
     * @param City $city
     * @param CityDTO $dto
     * @return City
     */
    public function update(City $city, CityDTO $dto)
    {
        $city->setName($dto->getName());
        return $city;
    }


    /**
     * @param $city
     * @param $id
     * @return bool
     */
    public function findCityByCountry($city, $id)
    {
        $data = $this->repo->getCityByCountryId($city, $id)->getQuery()->getOneOrNullResult();
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