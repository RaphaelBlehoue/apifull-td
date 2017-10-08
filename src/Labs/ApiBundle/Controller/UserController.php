<?php

namespace Labs\ApiBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        return [
            'raphael' => 'blehoue',
            'frejus'  => 'bodji'
        ];
    }
}
