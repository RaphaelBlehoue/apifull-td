<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/12/2017
 * Time: 10:12
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Labs\ApiBundle\Repository\UserRepository;

class UserManager extends ApiEntityManager
{

    /**
     * @var UserRepository
     */
    protected $repo;

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
}