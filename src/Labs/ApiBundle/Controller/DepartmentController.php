<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 05/11/2017
 * Time: 22:49
 */

namespace Labs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Labs\ApiBundle\Entity\Department;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;


class DepartmentController extends Controller
{
    /**
     * Create Department , reference many categories
     *
     * @ApiDoc(
     *     section="Departments",
     *     resource=true,
     *     authentication=true,
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Valid unique name department"},
     *        {"name"="position", "dataType"="integer", "required"=true, "description"="Render position, integer value"},
     *        {"name"="color_code", "dataType"="string", "required"=true, "description"="Color code hex, example: #fff"}
     *     },
     *     output={
     *        "class"=Department::class,
     *        "groups"={"department"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     }
     * )
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