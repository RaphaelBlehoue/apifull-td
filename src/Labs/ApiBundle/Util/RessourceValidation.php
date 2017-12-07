<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/11/2017
 * Time: 14:34
 */

namespace Labs\ApiBundle\Util;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ResourceValidation
{

    /**
     * @param $errors
     * @return array|void
     * $errors type is ConstraintViolationListInterface
     */
    public function DataValidation($errors)
    {
        if ( !$errors instanceof ConstraintViolationListInterface) {
           return;
        }

        $message = [
            'status'           => 'Failure',
            'exception'        => 'ResourcesValidationsErrors',
            'message'          => 'Validation Failed',
            'statusCode'       => Response::HTTP_BAD_REQUEST,
            'InternalCode'     => 1400
        ];
        $error = [];
        foreach ($errors as $key => $validationError) {
            $error['payload']['errors'][$key] = [
                'field' => $validationError->getPropertyPath(),
                'message' =>$validationError->getMessage()
            ];
        }
        $data = array_merge($message, $error);
        return $data;
    }
}