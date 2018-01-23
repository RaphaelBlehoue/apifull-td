<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 10/10/2017
 * Time: 17:24
 */

namespace Labs\ApiBundle\Event;


use Symfony\Component\HttpFoundation\Response;

class GetResponseUserEvent extends UserEvent
{
    /**
     * @var
     */
    protected $response;

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}