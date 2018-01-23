<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 10:56
 */

namespace Labs\ApiBundle\Event;



use Symfony\Component\HttpFoundation\Response;

class GetResponseNotificationEvent extends NotificationEvent
{
    /**
     * @var Response
     */
    private $response;


    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}