<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 22/01/2018
 * Time: 18:46
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Notification;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Repository\NotificationRepository;

/**
 * Class NotificationManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.notification_manager", public=true)
 */
class NotificationManager extends ApiEntityManager
{

    /**
     * @var NotificationRepository
     */
    protected $repo;


    /**
     * NotificationManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    /**
     * @return $this
     */
    protected function setRepository()
    {
        $this->repo = $this->em->getRepository(Notification::class);
        return $this;
    }

    /**
     * @return $this
     */
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
        $this->qb->orderBy('n.'.$column, $direction);
        return $this;
    }

    /**
     * @param Notification $notification
     * @param User $user
     * @return Notification
     */
    public function create(Notification $notification, User $user){
        $notification->setUser($user);
        $this->em->persist($notification);
        $this->em->flush();
        return $notification;
    }

    /**
     * @param Notification $notification
     * @param $fieldName
     * @param $fieldValue
     * @return Notification
     * Mise a jours du status
     */
    public function patch(Notification $notification, $fieldName, $fieldValue){

        if ($fieldName == 'status') {
            $notification->setStatusRead($fieldValue);
        }
        $this->em->merge($notification);
        $this->em->flush();
        return $notification;
    }
}