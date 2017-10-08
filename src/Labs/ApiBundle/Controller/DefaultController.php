<?php

namespace Labs\ApiBundle\Controller;

use Labs\ApiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends Controller
{
    /**
     * @Rest\Post("/register", name="register")
     * @Rest\View()
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        //$em = $this->get('doctrine')->getManager();
        //$encoder = $this->container->get('security.password_encoder');
        $data = $request->getContent();
        $user = $this->get('jms_serializer')->deserialize($data, 'Labs\ApiBundle\Entity\User', 'json');
        //$response = $serializer->deserialize($data, 'Labs\ApiBundle\Entity\User', 'json', []);
        //$username = $request->request->get('_username');
        //$password = $request->request->get('_password');

        //$user = new User($username);
        //$user->setPassword($encoder->encodePassword($user, $password));

        //$em->persist($user);
        //$em->flush();
        dump($user); die;
        //return $user->getUsername();
    }

    /**
     * @Rest\Get("/verifed", name="verifed")
     */
    public function verificatedAction()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}
