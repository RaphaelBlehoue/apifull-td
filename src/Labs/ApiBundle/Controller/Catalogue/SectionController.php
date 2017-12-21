<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 11/12/2017
 * Time: 16:49
 */

namespace Labs\ApiBundle\Controller\Catalogue;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\SectionDTO;
use Labs\ApiBundle\Entity\Category;
use Labs\ApiBundle\Entity\Section;
use Labs\ApiBundle\Manager\SectionManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SectionController extends BaseApiController
{
    /**
     * @var SectionManager
     */
    protected $sectionManager;

    /**
     * SectionController constructor.
     * @param SectionManager $sectionManager
     * @DI\InjectParams({
     *     "sectionManager" = @DI\Inject("api.section_manager")
     * })
     */
    public function __construct(SectionManager $sectionManager)
    {
        $this->sectionManager = $sectionManager;
    }

    /**
     * Get the list of all Section
     *
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Section",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Section::class,
     *        "groups"={"section","category"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Section found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     *
     * @Rest\Get("/categories/{category_id}/sections", name="_api_list", requirements = {"category_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"section"})
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="name", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Category $category
     * @return array
     */
    public function getSectionsAction($page, $limit, $orderBy, $orderDir, Category $category)
    {
        return $this->sectionManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     * Get One Section resource
     *
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Section resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Section found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/categories/{category_id}/sections/{id}", name="_api_show", requirements = {"id"="\d+", "category_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"section","category"})
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @param Category $category
     * @param Section $section
     * @return \FOS\RestBundle\View\View|Section
     */
    public function getSectionAction(Category $category, Section $section)
    {
        $checkIsExist = $this->sectionManager->findSectionByCategory($category, $section);
        if ($checkIsExist === false){
            return $this->view('Not Found Category reference', Response::HTTP_BAD_REQUEST);
        }
        return $section;
    }


    /**
     * Create a new Section Resource
     *
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Section Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Section name"}
     *     },
     *     output={
     *        "class"=Section::class,
     *        "groups"={"section"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Section Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/categories/{category_id}/sections", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"section"})
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @ParamConverter(
     *     "section",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"section_default"} }}
     * )
     * @param Category $category
     * @param Section $section
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createSectionAction(Category $category, Section $section, ConstraintViolationListInterface $validationErrors){

        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->sectionManager->create($category, $section);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_section_api_show' ,
                [
                'category_id' => $data->getCategory()->getId(),
                'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Section
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Section Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Section Name"},
     *        {"name"="online", "dataType"="boolean", "required"=false, "description"="CatSectionegory status"}
     *     },
     *     statusCodes={
     *        200="Section  update  Resource Successfully",
     *        204="Section  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/categories/{category_id}/sections/{id}", name="_api_updated", requirements = {"id"="\d+", "category_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"section"})
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @ParamConverter("section")
     * @ParamConverter(
     *     "sectionDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Category $category
     * @param Section $section
     * @param SectionDTO $sectionDTO
     * @return \FOS\RestBundle\View\View|Section
     */
    public function updateSectionAction(Category $category, Section $section, SectionDTO $sectionDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($sectionDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->sectionManager->update($section, $sectionDTO);
        $valid = $validator->validate($data, null, 'section_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $section;
    }


    /**
     *
     * Partial Update Online field an exiting Section
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Online field an existing Section Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="Online", "dataType"="boolean", "required"=true, "description"="Online Section"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Section  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/categories/{category_id}/sections/{id}/online", name="_api_patch_online", requirements = {"id"="\d+", "category_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @ParamConverter("section")
     * @param Category $category
     * @param Section $section
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchSectionOnlineAction(Category $category, Section $section, Request $request)
    {
        return $this->patch($section, $request, 'online');
    }


    /**
     *
     * Delete an existing Section
     * @ApiDoc(
     *     section="Category.Section",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Section Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Section has been successfully deleted",
     *        400="Returned if Section does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/categories/{category_id}/sections/{id}", name="_api_delete", requirements = {"id"="\d+", "category_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("category", class="LabsApiBundle:Category", options={"id" = "category_id"})
     * @ParamConverter("section")
     * @param Category $category
     * @param Section $section
     */
    public function removeSectionAction(Category $category, Section $section){
         $this->sectionManager->delete($section);
    }

    /**
     * @param Section $section
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View|Section
     */
    private function patch(Section $section, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorField($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->sectionManager->patch($section, $fieldName, $field);
    }


}