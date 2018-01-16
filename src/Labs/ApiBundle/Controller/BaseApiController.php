<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 06/12/2017
 * Time: 23:51
 */

namespace Labs\ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Exception\InvalidParameterException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


abstract class BaseApiController extends FOSRestController
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEm(){
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $error
     * @return \FOS\RestBundle\View\View
     */
    public function handleError($error){
        return $this->validate($error);
    }

    /**
     * @param $field
     * @param $fieldName
     * @return array
     */
    public function handleErrorField($field, $fieldName)
    {
        $error = [];
        if (!is_bool($field) || $field === null){
            $error[] = [
                'field'   => $fieldName,
                'message' => 'Invalid Type'
            ];
        }
        return $error;
    }


    /**
     * @param $error
     * @return \FOS\RestBundle\View\View
     */
    private function validate($error){
        if ($error instanceof ConstraintViolationListInterface || $error instanceof ValidatorInterface){
            $errors = [];
            foreach ($error as $key => $validationError) {
                $errors['errors'][] = [
                    'status' => false,
                    'field' => $validationError->getPropertyPath(),
                    'message' =>$validationError->getMessage()
                ];
            }
            $message = [
                'message'          => 'Validation Failed',
                'statusCode'       => Response::HTTP_BAD_REQUEST
            ];
            $data = array_merge($message, $errors);
            return $this->view($data, Response::HTTP_BAD_REQUEST);
        }
        throw new InvalidParameterException('Parameter type invalid');
    }

    protected function NotFound(array $message){
        return $this->view($message, Response::HTTP_BAD_REQUEST);
    }
}