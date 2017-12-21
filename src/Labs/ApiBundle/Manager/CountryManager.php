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
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\DTO\CountryDTO;
use Labs\ApiBundle\Repository\CountryRepository;


/**
 * Class CountryManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.country_manager", public=true)
 *
 */
class CountryManager extends ApiEntityManager
{
    /**
     * @var CountryRepository
     */
    protected $repo;

    /**
     * CountryManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Country');
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
     * @inheritdoc({creation du Country})
     * @param Country $country
     * @return Country
     */
    public function create(Country $country)
    {
        $this->em->persist($country);
        $this->em->flush();
        return $country;
    }


    public function update(Country $country, CountryDTO $dto)
    {
        $country->setName($dto->getName())
            ->setCode($dto->getCode());
        return $country;
    }

}