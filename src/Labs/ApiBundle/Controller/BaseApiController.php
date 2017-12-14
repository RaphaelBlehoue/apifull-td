<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 06/12/2017
 * Time: 23:51
 */

namespace Labs\ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
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
     * @param $entity
     * @param $entityDTO
     * @param string $validation_groups
     * @return \FOS\RestBundle\View\View
     */
    public function updated($entity, $entityDTO, $validation_groups = "Default"){
        $entityInstance = get_class($entity);
        $entityInstanceDTO = get_class($entityDTO);
        if ( !$entity instanceof $entityInstance) {
            return;
        }
        if ( !$entityDTO instanceof $entityInstanceDTO) {
            return;
        }

        $validator = $this->container->get('validator');
        $violationsDTO = $validator->validate($entityDTO);
        if (count($violationsDTO) > 0) {
            return $this->getValidator($violationsDTO);
        }
        if (is_callable([$entity, 'updateFromDTO'])) {
            $entity->updateFromDTO($entityDTO);
            $violations = $validator->validate($entity, null, [$validation_groups]);

            if (count($violations) > 0) {
                return $this->getValidator($violations);
            }
            $this->getEm()->flush();
            return $this->view('Updated Successfully', Response::HTTP_NO_CONTENT);
        }
        throw new Exception('Class Entity Not Found');
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

    /**
     * @param  $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function EntityValidateErrors($validationErrors)
    {
        $data = $this->get('labs_api.util.ressource_validation')->DataValidation($validationErrors);
        return $this->view($data, Response::HTTP_BAD_REQUEST);
    }
}