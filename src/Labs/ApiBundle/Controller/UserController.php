<?php

namespace Labs\ApiBundle\Controller;
use FOS\RestBundle\View\View;
use Labs\ApiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/register", name="register")
     * @param Request $request
     * @return User
     */
    public function registerAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $user = new User();
        $encoder = $this->get('security.password_encoder');
        $username = $request->get('username');
        $password = $request->get('password');
        $user->setPassword($encoder->encodePassword($user, $password))
            ->setUsername($username)
        ;
        $em->persist($user);
        $em->flush();
        return $user;
    }
}
