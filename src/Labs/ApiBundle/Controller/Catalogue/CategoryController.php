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
     *        "groups"={"category_department","category"},
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
     * @Rest\Get("/departments/{id}/categories", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category","department"})
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
     *         200="Return when Department found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/departments/{department_id}/categories/{id}", name="_api_show", requirements = {"id"="\d+", "department_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"category"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "department_id"})
     * @param Department $department
     * @param Category $category
     * @return \FOS\RestBundle\View\View
     */
    public function getCategoryAction(Department $department, Category $category)
    {
        $getOneCategory = $this->getEm()->getRepository('LabsApiBundle:Category')->findOneBy([
            'department' => $department
        ]);
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
     *        201="Return when Department Resource Created Successfully",
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

    
    public function updateCategoryAction(Department $department, Category $category){

    }

    public function patchCategoryTopAction(Department $department, Category $category){}

    public function patchCategoryOnlineAction(Department $department, Category $category){}

    public function removeCategoryAction(Department $department, Category $category){}

}