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
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\Entity\Store;
use Labs\ApiBundle\DTO\StoreDTO;
use Labs\ApiBundle\Entity\Street;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Repository\StoreRepository;


/**
 * Class StoreManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.store_manager", public=true)
 */
class StoreManager extends ApiEntityManager
{
    /**
     * @var StoreRepository
     */
    protected $repo;

    /**
     * StoreManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Store');
        return $this;
    }

    public function getList()
    {
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    public function order($column, $direction)
    {
        $this->qb->orderBy('s.'.$column, $direction);
        return $this;
    }

    /**
     * @inheritdoc({creation du store})
     * @param StoreDTO $storeInput
     * @param Department $department
     * @param Street $street
     * @param User $user
     * @return Store
     */
    public function create(Store $store, Department $department, Street $street, User $user)
    {
        $store->setUser($user);
        $store->setDepartment($department);
        $store->setStreet($street);
        $this->em->persist($store);
        $this->em->flush();
        return $store;
    }


    public function update(Store $store, StoreDTO $dto)
    {
        $store->setName($dto->getName())
            ->setPhone($dto->getPhone())
            ->setContent($dto->getContent());
        return $store;
    }

    /**
     * @param $department
     * @param $street
     * @param $id
     * @return bool
     */
    public function findDepartmentStreetByStore($department,$street, $id)
    {
        $data = $this->repo->getDepartmentStreetByStore($department, $street, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}