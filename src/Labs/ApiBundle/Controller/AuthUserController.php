<?php

namespace Labs\ApiBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthUserController extends Controller
{
    /**
     * @param Request $request
     * @return Request
     * @Rest\Post("/auth")
     * @Rest\View()
     */
    public function  authenticatedUserAction(Request $request)
    {

    }
}
