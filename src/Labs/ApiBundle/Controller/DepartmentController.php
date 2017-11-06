<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 05/11/2017
 * Time: 22:49
 */

namespace Labs\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Labs\ApiBundle\Entity\Department;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;


class DepartmentController extends Controller
{
    /**
     * @Rest\Post("/departments")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @ParamConverter(
     *     "department",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups"={"default"}}}
     * )
     * @param Department $department
     * @param ConstraintViolationListInterface $validationErrors
     */
    public function postDepartmentsAction(Department $department, ConstraintViolationListInterface $validationErrors)
    {
    }
}