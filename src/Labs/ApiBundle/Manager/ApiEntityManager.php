<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/12/2017
 * Time: 20:16
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class ApiEntityManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * @var QueryBuilder
     */
    protected $qb;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->setRepository();
    }

    abstract protected function setRepository();

    abstract public function getList();


    /**
     * @return QueryBuilder
     */
    public function QB()
    {
        return $this->qb;
    }

    public function get($id){
        return $this->repo->find($id);
    }

    /**
     * @param $entiy
     * @return mixed
     */
    public function reload($entiy)
    {
        $this->em->refresh($entiy);
        return $entiy;
    }

    /**
     * @param $entity
     */
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    abstract public function order($column, $direction);


    /**
     * @param $page
     * @param $limit
     * @return array
     */
    public function paginate($page, $limit)
    {
        $this->qb->setMaxResults($limit)->setFirstResult(($page - 1) * $limit);
        return $this->getAll();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->qb->getQuery()->getResult();
    }

}