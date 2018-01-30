<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 25/01/2018
 * Time: 00:16
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Command;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Repository\CommandRepository;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class CommandManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.order_manager", public=true)
 */
class CommandManager extends ApiEntityManager
{

    /**
     * @var CommandRepository
     */
    protected $repo;


    /**
     * CommandManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em){
        parent::__construct($em);
    }

    /**
     * @return $this
     */
    protected function setRepository()
    {
        $this->repo = $this->em->getRepository(Command::class);
        return $this;
    }

    /**
     * @return $this
     */
    public function getList(){
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function getListWithParams($user)
    {
        $this->qb = $this->repo->getListWithParamsQB($user);
        return $this;
    }

    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('cmd.'.$column, $direction);
        return $this;
    }

    /**
     * @param User $user
     * @param Command $command
     * @return Command
     */
    public function create(User $user, Command $command, $origin)
    {
        $command->setUser($user);
        $command->setOrigin($origin);
        $this->em->persist($command);
        $this->em->flush();
        return $command;
    }

    /**
     * @param Command $command
     * @param $fieldName
     * @param $fieldValue
     * @return Command
     */
    public function patch(Command $command, $fieldName, $fieldValue){

        if ($fieldName == 'status') {
            $command->setStatus($fieldValue);
        }
        $this->em->merge($command);
        $this->em->flush();
        return $command;
    }

    /**
     * @param $user
     * @param $order
     * @return bool
     */
    public function findUserByOrder($user, $order){
        if (!$user instanceof User){
            throw new UnexpectedTypeException($user, __NAMESPACE__.'\User');
        }
        if (!$order instanceof Command){
            throw new UnexpectedTypeException($order, __NAMESPACE__.'\CommandManager');
        }
        $data = $this->repo->getUserByOrderId($user, $order)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}