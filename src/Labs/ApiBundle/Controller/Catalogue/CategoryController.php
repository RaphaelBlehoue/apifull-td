<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 08/12/2017
 * Time: 11:41
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\CategoryDTO;
use Labs\ApiBundle\Entity\Category;
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\Manager\CategoryManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CategoryController extends BaseApiController
{

    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    /**
     * CategoryController constructor.
     * @param CategoryManager $categoryManager
     * @DI\InjectParams({
     *     "categoryManager" = @DI\Inject("api.category_manager")
     * })
     */
    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * Get the list of all Categories
     *
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Categories",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Category::class,
     *        "groups"={"department","category"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Category found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/departments/{departmentId}/categories", name="_api_list", requirements = {"departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category","section"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="name", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Department $department
     * @return array
     */
    public function getCategoriesAction($page, $limit, $orderBy, $orderDir, Department $department)
    {
        return $this->categoryManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get One Category resource
     *
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Category resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Category found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/departments/{departmentId}/categories/{id}", name="_api_show", requirements = {"id"="\d+", "departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category","section"})
     * @ParamConverter("department", converter="doctrine.orm", options={"id" = "departmentId"})
     * @ParamConverter("category", class="LabsApiBundle:Category")
     * @param Department $department
     * @param Category $category
     * @return \FOS\RestBundle\View\View|Category
     */
    public function getCategoryAction(Department $department, Category $category)
    {
        $checkIsExist = $this->categoryManager->findCategoryByDepartement($department, $category);
        if ($checkIsExist === false){
            return $this->view('Not Found Department reference', Response::HTTP_BAD_REQUEST);
        }
        return $category;
    }


    /**
     * Create a new Category Resource
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Category name"}
     *     },
     *     output={
     *        "class"=Category::class,
     *        "groups"={"category"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Category Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/departments/{departmentId}/categories", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"category"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter(
     *     "category",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"category_default"} }}
     * )
     * @param Department $department
     * @param Category $category
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createCategoryAction(Department $department, Category $category, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->categoryManager->create($department, $category);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_category_api_show',
                [
                    'departmentId' => $data->getDepartment()->getId(),
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     *
     * Update an exiting Category
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Category Name"},
     *        {"name"="top", "dataType"="boolean", "required"=false, "description"="Category Top"},
     *        {"name"="online", "dataType"="boolean", "required"=false, "description"="Category status"}
     *     },
     *     statusCodes={
     *        200="Category  update  Resource Successfully",
     *        204="Category  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/departments/{departmentId}/categories/{id}", name="_api_updated", requirements = {"id"="\d+", "departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("department", converter="doctrine.orm", options={"id" = "departmentId"})
     * @ParamConverter("category", class="LabsApiBundle:Category")
     * @ParamConverter(
     *     "categoryDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Department $department
     * @param Category $category
     * @param CategoryDTO $categoryDTO
     * @return \FOS\RestBundle\View\View|Category
     */
    public function updateCategoryAction(Department $department, Category $category, CategoryDTO $categoryDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($categoryDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->categoryManager->update($category, $categoryDTO);
        $valid = $validator->validate($data, null, 'category_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $category;
    }


    /**
     *
     * Partial Update Top field an exiting Category
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Top field an existing Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="Top", "dataType"="boolean", "required"=true, "description"="Top Category"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Category  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/departments/{departmentId}/categories/{id}/top", name="_api_patch_top", requirements = {"id"="\d+", "departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchCategoryTopAction(Department $department, Category $category, Request $request){
        return $this->patch($category, $request, 'top');
    }


    /**
     *
     * Partial Update Online field an exiting Category
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Online field an existing Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="Online", "dataType"="boolean", "required"=true, "description"="Online Category"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Category  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/departments/{departmentId}/categories/{id}/online", name="_api_patch_online", requirements = {"id"="\d+", "departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Category
     */
    public function patchCategoryOnlineAction(Department $department, Category $category, Request $request){
        return $this->patch($category, $request, 'online');
    }


    /**
     *
     * Delete an existing Category
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Category has been successfully deleted",
     *        400="Returned if Category does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/departments/{departmentId}/categories/{id}", name="_api_delete", requirements = {"id"="\d+", "departmentId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     */
    public function removeCategoryAction(Department $department, Category $category){
        $this->categoryManager->delete($category);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View|Category
     */
    private function patch(Category $category, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorField($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->categoryManager->patch($category, $fieldName, $field);
    }

}