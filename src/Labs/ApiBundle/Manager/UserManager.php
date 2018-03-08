<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/12/2017
 * Time: 10:12
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Repository\UserRepository;


/**
 * Class UserManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.user_manager", public=true)
 */

class UserManager extends ApiEntityManager
{

    /**
     * @var UserRepository
     */
    protected $repo;

    /**
     * UserManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:User');
    }

    public function getList()
    {
        $this->repo = $this->repo->getListQB();
        return $this;
    }

    public function order($column, $direction)
    {
        $this->qb->orderBy('u.'.$column, $direction);
    }

    /**
     * @param $username
     * @return mixed
     */
    public function isExistParams($username){
        return $this->repo->findByField($username);
    }
}
