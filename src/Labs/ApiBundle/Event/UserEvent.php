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
use Symfony\Component\HttpFoundation\Request;

class UserEvent extends Event
{
    protected $user;

    protected $request;

    protected $formParameterNamed;

    public function __construct(User $user, Request $request, $formParameterNamed)
    {
        $this->user = $user;
        $this->request = $request;
        $this->formParameterNamed = $formParameterNamed;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getFormParameterNamed()
    {
        return $this->formParameterNamed;
    }

}