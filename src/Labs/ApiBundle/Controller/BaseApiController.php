<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 06/12/2017
 * Time: 23:51
 */

namespace Labs\ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends FOSRestController
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEm(){
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $errors
     * @return \FOS\RestBundle\View\View
     */
    public function getValidator($errors){
        $errorsConfig = [];
        foreach ($errors as $errorKey => $errorValue )
        {
            $errorsConfig['errors'][$errorKey] = [
                'field' => $errorValue->getPropertyPath(),
                'message' => $errorValue->getMessage()
            ];
        }
        return  $this->view($errorsConfig, Response::HTTP_BAD_REQUEST);
    }
}