<?php

namespace Labs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Labs\ApiBundle\DTO\DepartmentDTO;
use Labs\ApiBundle\Entity\Department;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class DepartmentController extends BaseApiController
{

    /**
     * Get the list of all Departments
     *
     * @ApiDoc(
     *     section="Departments",
     *     resource=true,
     *     authentication=true,
     *     description="Get the list of all Departments",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Department::class,
     *        "groups"={"department"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Department found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     *
     * @Rest\Get("/departments", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"department"})
     *
     */
    public function getDepartmentsAction()
    {
        $departments = $this->getEm()->getRepository('LabsApiBundle:Department')
            ->findAll();
        return $this->view($departments, Response::HTTP_OK);
    }


    /**
     * Get One Department resource
     *
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Department",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Department found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/departments/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"department"})
     * @param Department $department
     * @return \FOS\RestBundle\View\View|Department|null|object
     */
    public function getDepartmentAction(Department $department){
        $data = $this->getEm()->getRepository('LabsApiBundle:Department')->find($department);
        if (null === $data) {
            return $this->view(['message' => 'Departement Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->view($data, Response::HTTP_OK);
    }


    /**
     * Create a new Department Resource
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Department Resource",
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
     *     },
     *     statusCodes={
     *        201="Return when Department Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/departments", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"department"})
     * @ParamConverter(
     *     "department",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"department_default"} }}
     * )
     * @param Department $department
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createDepartmentAction(Department $department, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0 ) {
            $error = $this->get('labs_api.util.resource_validation')->DataValidation($validationErrors);
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        $department->__construct();
        $this->getEm()->persist($department);
        $this->getEm()->flush();
        /** @var Department $department */
        return $this->view($department, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_department_api_show' ,['id' => $department->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     *
     * Update an exiting Department  (this reference many categories)
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Department Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Valid unique name department"},
     *        {"name"="position", "dataType"="integer", "required"=true, "description"="Render position, integer value"},
     *        {"name"="color_code", "dataType"="string", "required"=true, "description"="Color code hex, example: #fff"},
     *        {"name"="top", "dataType"="boolean", "required"=true, "description"="Top department"},
     *        {"name"="online", "dataType"="boolean", "required"=true, "description"="status department"}
     *     },
     *     statusCodes={
     *        200="Department  update  Resource Successfully",
     *        204="Department  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/departments/{id}", name="_api_updated", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("department")
     * @ParamConverter(
     *     "departmentDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Department $department
     * @param DepartmentDTO $departmentDTO
     * @return \FOS\RestBundle\View\View
     * @internal param Request $request
     */
    public function updateDepartmentAction(Department $department, DepartmentDTO $departmentDTO)
    {
        if (!$department){
            return $this->view('Not found Department', Response::HTTP_NOT_FOUND);
        }
        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($departmentDTO);

        if (count($violationsDTO) > 0) {
            return $this->getValidator($violationsDTO);
        }

        $department->updateFromDTO($departmentDTO);
        $violations = $validator->validate($department, null, ["department_default"]);

        if (count($violations) > 0) {
            return $this->getValidator($violations);
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->view('Updated Successfully', Response::HTTP_OK);
    }


    /**
     *
     * Partial Update Top field an exiting Department  (this reference many categories)
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Top field an existing Department Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="top", "dataType"="boolean", "required"=true, "description"="Top department"},
     *     },
     *     statusCodes={
     *        204=" Return Partial Department  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/departments/{id}/top", name="_api_patch_top", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @param Department $department
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchDepartmentTopAction(Department $department, Request $request)
    {
        return $this->patchDepartment($department, $request, 'top');
    }

    /**
     *
     * Partial Update Online field an exiting Department  (this reference many categories)
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Online field an existing Department Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="online", "dataType"="boolean", "required"=true, "description"="Online department"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Department  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/departments/{id}/online", name="_api_patch_online", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @param Department $department
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchDepartmentOnlineAction(Department $department, Request $request)
    {
        return $this->patchDepartment($department, $request, 'online');
    }


    /**
     *
     * Delete an existing Department  (this reference many categories)
     * @ApiDoc(
     *     section="Departments",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Department Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Department has been successfully deleted",
     *        400="Returned if Department does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/departments/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Department $department
     * @return \FOS\RestBundle\View\View
     */
    public function removeDepartmentAction(Department $department) {
        if (!$department){
            return $this->view('Not found Department', Response::HTTP_NOT_FOUND);
        }
        $this->getEm()->remove($department);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }


    /**
     * @param Department $department
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View
     */
    private function patchDepartment(Department $department, Request $request, $fieldName)
    {
        if (!$department){
            return $this->view('Not found Department', Response::HTTP_NOT_FOUND);
        }
        $error = [];
        $field = $request->get($fieldName);
        if (!is_bool($field)){
            $error[] = [
                'field'   => $fieldName,
                'message' => 'Invalid Type'
            ];
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }

        if ($fieldName == 'top') {
            $department->setTop($field);
        }else{
            $department->setOnline($field);
        }
        $this->getEm()->merge($department);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }
}
