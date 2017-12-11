<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 08/12/2017
 * Time: 11:41
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use FOS\RestBundle\Controller\Annotations as Rest;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\CategoryDTO;
use Labs\ApiBundle\Entity\Category;
use Labs\ApiBundle\Entity\Department;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CategoryController extends BaseApiController
{

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
     *
     * @Rest\Get("/departments/{department_id}/categories", name="_api_list", requirements = {"department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category","department","section"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @param Department $department
     * @return \FOS\RestBundle\View\View
     */
    public function getCategoriesAction(Department $department)
    {
        if (!$department){
            return $this->view('Not Found Department', Response::HTTP_NOT_FOUND);
        }
        return $this->view($department->getCategory(), Response::HTTP_OK);
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
     * @Rest\Get("/departments/{department_id}/categories/{id}", name="_api_show", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category","department","section"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @param Department $department
     * @param Category $category
     * @return \FOS\RestBundle\View\View
     */
    public function getCategoryAction(Department $department, Category $category)
    {
        $repository = $this->getEm()->getRepository('LabsApiBundle:Category');
        $getOneCategory = $repository->getOneCategoryDepartment($department, $category);
        if (null === $getOneCategory){
            return $this->view('Not Found Category', Response::HTTP_NOT_FOUND);
        }
        return $this->view($getOneCategory, Response::HTTP_OK);
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
     * @Rest\Post("/departments/{department_id}/categories", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"category"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
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
    public function createCategoryAction(Department $department, Category $category, ConstraintViolationListInterface $validationErrors){
        if (!$department){return $this->view('Not found Department', Response::HTTP_NOT_FOUND);}
        if (count($validationErrors)) {return $this->EntityValidateErrors($validationErrors);}
        $category->__construct();
        $category->setDepartment($department);
        $this->getEm()->persist($category);
        $this->getEm()->flush();
        return $this->view($category, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_category_api_show' ,[
                    'department_id' => $department->getId(),
                    'id' => $category->getId()
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
     * @Rest\Put("/departments/{department_id}/categories/{id}", name="_api_updated", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @ParamConverter("category")
     * @ParamConverter(
     *     "categoryDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Department $department
     * @param Category $category
     * @param CategoryDTO $categoryDTO
     * @return \FOS\RestBundle\View\View
     */
    public function updateCategoryAction(Department $department, Category $category, CategoryDTO $categoryDTO){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Category');
        $getPutData = $repository->getOneCategoryDepartment($department, $category);
        if (!$getPutData){
            return $this->view('Not found Resource', Response::HTTP_NOT_FOUND);
        }
        $groups_validation = "category_default";
        return $this->updated($category, $categoryDTO, $groups_validation);
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
     * @Rest\Patch("/departments/{department_id}/categories/{id}/top", name="_api_patch_top", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchCategoryTopAction(Department $department, Category $category, Request $request){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Category');
        $getPatchData = $repository->getOneCategoryDepartment($department, $category);
        if (!$getPatchData){
            return $this->view('Not found Resource', Response::HTTP_NOT_FOUND);
        }
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
     * @Rest\Patch("/departments/{department_id}/categories/{id}/online", name="_api_patch_online", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchCategoryOnlineAction(Department $department, Category $category, Request $request){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Category');
        $getPutData = $repository->getOneCategoryDepartment($department, $category);
        if (!$getPutData){
            return $this->view('Not found Resource', Response::HTTP_NOT_FOUND);
        }
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
     * @Rest\Delete("/departments/{department_id}/categories/{id}", name="_api_delete", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @ParamConverter("category")
     * @param Department $department
     * @param Category $category
     * @return \FOS\RestBundle\View\View
     */
    public function removeCategoryAction(Department $department, Category $category){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Category');
        $getPutData = $repository->getOneCategoryDepartment($department, $category);
        if (!$getPutData){
            return $this->view('Not found Resource Category', Response::HTTP_NOT_FOUND);
        }
        $this->getEm()->remove($category);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View
     */
    private function patch(Category $category, Request $request, $fieldName)
    {
        if (!$category){
            return $this->view('Not found Resource Category', Response::HTTP_NOT_FOUND);
        }
        $error = [];
        $field = $request->get($fieldName);
        if (!is_bool($field) || $field === null){
            $error[] = [
                'field'   => $fieldName,
                'message' => 'Invalid Type'
            ];
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }

        if ($fieldName == 'top') {
            $category->setTop($field);
        }else{
            $category->setOnline($field);
        }
        $this->getEm()->merge($category);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

}