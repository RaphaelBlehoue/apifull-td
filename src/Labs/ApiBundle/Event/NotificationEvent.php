<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace Labs\ApiBundle\Event;


use Labs\ApiBundle\Entity\Notification;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class NotificationEvent extends Event
{

    /**
     * @var Notification
     */
    protected $notification;

    /**
     * @var Request
     */
    protected $request;


    public function __construct(Notification $notification, Request $request = null)
    {
        $this->notification = $notification;
        $this->request = $request;
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

}