<?php

/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/10/2017
 * Time: 15:07
 */


namespace Labs\ApiBundle\Security\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class JWTInvalidListener
{
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse('Votre session a expire, connectez-vous pour continuer', 403);
        $event->setResponse($response);
    }
}