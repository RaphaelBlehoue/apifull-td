<?php

namespace Labs\ApiBundle\Controller\Catalogue;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\DepartmentDTO;
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\Manager\DepartmentManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;


/**
 * Class DepartmentController
 * @package Labs\ApiBundle\Controller\Catalogue
 * @App\RestResult
 */
class DepartmentController extends BaseApiController
{


    /**
     * @var DepartmentManager
     */
    protected $departmentManager;

    /**
     * DepartmentController constructor.
     * @param DepartmentManager $departmentManager
     * @DI\InjectParams({
     *     "departmentManager" = @DI\Inject("api.department_manager")
     * })
     */
    public function __construct(DepartmentManager $departmentManager)
    {
        $this->departmentManager = $departmentManager;
    }

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
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/departments", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"department","category"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="position", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getDepartmentsAction($page, $limit, $orderBy, $orderDir)
    {
        return $this->departmentManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
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
     * @ParamConverter("department", class="LabsApiBundle:Department")
     * @param Department $department
     * @return Department
     */
    public function getDepartmentAction(Department $department){
        return $department;
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
            return $this->handleError($validationErrors);
        }
        $data = $this->departmentManager->create($department);
        return $this->view($data, Response::HTTP_CREATED,[
            'Location' => $this->generateUrl('get_department_api_show',
                [
                    'id' => $department->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
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
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department")
     * @ParamConverter(
     *     "departmentDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Department $department
     * @param DepartmentDTO $departmentDTO
     * @return \FOS\RestBundle\View\View|Department
     */
    public function updateDepartmentAction(Department $department, DepartmentDTO $departmentDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($departmentDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->departmentManager->update($department, $departmentDTO);
        $valid = $validator->validate($data, null, 'department_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $department;
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
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"department"})
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
     */
    public function removeDepartmentAction(Department $department) {
        $this->departmentManager->delete($department);
    }


    /**
     * @param Department $department
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View
     */
    private function patchDepartment(Department $department, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorField($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->departmentManager->patch($department, $fieldName, $field);
    }
}
