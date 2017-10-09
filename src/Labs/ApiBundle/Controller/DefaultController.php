<?php

namespace Labs\ApiBundle\Controller;

use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Event\UserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $em = $this->get('doctrine')->getManager();
        $encoder = $this->container->get('security.password_encoder');
        //$data = $request->getContent();
        //$user = $this->get('jms_serializer')->deserialize($data, 'Labs\ApiBundle\Entity\User', 'json');
        //$response = $serializer->deserialize($data, 'Labs\ApiBundle\Entity\User', 'json', []);
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $user = new User();
        $user->setPassword($encoder->encodePassword($user, $password));

        /* Event dispatcher to set User role */
        $event = new UserEvent($user, $request,  '_action');
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(ApiEvents::SET_AUTO_USER_ROLE, $event);

        //$em->persist($user);
        //$em->flush();
        die('ok');
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
