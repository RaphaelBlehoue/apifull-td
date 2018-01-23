<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 13:06
 */

namespace Labs\ApiBundle\Event;


use Labs\ApiBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}