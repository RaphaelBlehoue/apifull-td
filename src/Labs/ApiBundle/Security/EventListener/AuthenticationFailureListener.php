<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/10/2017
 * Time: 14:58
 */

namespace Labs\ApiBundle\Security\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class AuthenticationFailureListener
{
    private $message = 'Identifiant incorrect, s\'il vous plaît verifié que vous avez rentré les information correctement';

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $errorMessage = [
            'statutCode'   => 401,
            'errors'       => true,
            'messageKey'   => '401 Unauthorized',
            'status'       => 'failure',
            'messageError' => $this->message
        ];
        $response= new JWTAuthenticationFailureResponse($errorMessage);
        $event->setResponse($response);
    }
}