<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 13/12/2017
 * Time: 13:46
 */

namespace Labs\ApiBundle\Controller\Setting;


use Labs\ApiBundle\Controller\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\DTO\StreetDTO;
use Labs\ApiBundle\Entity\City;
use Labs\ApiBundle\Entity\Street;
use Labs\ApiBundle\Manager\StreetManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class StreetController extends BaseApiController
{

    /**
     * @var StreetManager
     */
    protected $streetManager;

    /**
     * StreetController constructor.
     * @param StreetManager $streetManager
     *
     * @DI\InjectParams({
     *     "streetManager" = @DI\Inject("api.street_manager")
     * })
     */
    public function __construct(StreetManager $streetManager)
    {
        $this->streetManager = $streetManager;
    }

    /**
     * Get the list of all Streets
     *
     * @ApiDoc(
     *     section="Cities.Streets",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Streets",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Street::class,
     *        "groups"={"city","street"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Street found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/cities/{cityId}/streets", name="_api_list", requirements = {"cityId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"street"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "cityId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="name", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param City $city
     * @return array
     */
    public function getStreetsAction($page, $limit, $orderBy, $orderDir, City $city)
    {
        return $this->streetManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get One Street resource
     *
     * @ApiDoc(
     *     section="Cities.Streets",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Street resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Street found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/cities/{cityId}/streets/{id}", name="_api_show", requirements = {"id"="\d+", "cityId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"street"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "cityId"})
     * @param City $city
     * @param Street $street
     * @return \FOS\RestBundle\View\View|Street
     */
    public function getStreetAction(City $city, Street $street){
        $checkIsExist = $this->streetManager->findStreetByCity($city, $street);
        if ($checkIsExist === false){
            return $this->view('Not Found City reference', Response::HTTP_BAD_REQUEST);
        }
        return $street;
    }

    /**
     * Create a new Street Resource
     *
     * @ApiDoc(
     *     section="Cities.Streets",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Street Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Street name"}
     *     },
     *     output={
     *        "class"=Street::class,
     *        "groups"={"street"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Street Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/cities/{cityId}/streets", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"street"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "cityId"})
     * @ParamConverter(
     *     "street",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"street_default"} }}
     * )
     * @param City $city
     * @param Street $street
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createStreetAction(City $city, Street $street, ConstraintViolationListInterface $validationErrors)
    {

        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->streetManager->create($city, $street);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_street_api_show' ,
                [
                    'cityId' => $city->getId(),
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Street
     * @ApiDoc(
     *     section="Cities.Streets",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Street Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Street Name"},
     *     },
     *     statusCodes={
     *        200="Street  update  Resource Successfully",
     *        204="Street  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/cities/{cityId}/streets/{id}", name="_api_updated", requirements = {"id"="\d+", "cityId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "cityId"})
     * @ParamConverter("street")
     * @ParamConverter(
     *     "streetDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param City $city
     * @param Street $street
     * @param StreetDTO $streetDTO
     * @return \FOS\RestBundle\View\View|Street
     */
    public function updateStreetAction(City $city, Street $street, StreetDTO $streetDTO){

        $validator = $this->get('validator');
        $validDTO = $validator->validate($streetDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->streetManager->update($street, $streetDTO);
        $valid = $validator->validate($data, null, 'section_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $street;
    }

    /**
     *
     * Delete an existing Street
     * @ApiDoc(
     *     section="Cities.Streets",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Street Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Street has been successfully deleted",
     *        400="Returned if Street does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/cities/{cityId}/streets/{id}", name="_api_delete", requirements = {"id"="\d+", "cityId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "cityId"})
     * @ParamConverter("street")
     * @param City $city
     * @param Street $street
     */
    public function removeStreetAction(City $city, Street $street){
        $this->streetManager->delete($street);
    }
}