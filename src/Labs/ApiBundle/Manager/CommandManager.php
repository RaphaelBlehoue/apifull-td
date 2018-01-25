<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 25/01/2018
 * Time: 00:16
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Labs\ApiBundle\Repository\CommandRepository;

class CommandManager extends ApiEntityManager
{

    /**
     * @var CommandRepository
     */
    protected $repo;

    
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    protected function setRepository()
    {
        // TODO: Implement setRepository() method.
    }

    public function getList()
    {
        // TODO: Implement getList() method.
    }

    public function order($column, $direction)
    {
        // TODO: Implement order() method.
    }
}