<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 13/12/2017
 * Time: 13:46
 */

namespace Labs\ApiBundle\Controller\Setting;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\Entity\City;
use Labs\ApiBundle\DTO\CityDTO;
use Labs\ApiBundle\Manager\CityManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CityController extends BaseApiController
{
    /**
     * @var CityManager
     */
    private $cityManager;

    /**
     * CityController constructor.
     * @param CityManager $cityManager
     * @DI\InjectParams({
     *     "cityManager" = @DI\Inject("api.city_manager")
     * })
     */
    public function __construct(CityManager $cityManager)
    {
        $this->cityManager = $cityManager;
    }

    /**
     * Get the list of all Cities
     *
     * @ApiDoc(
     *     section="Countries.City",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Cities",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=City::class,
     *        "groups"={"country","city"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when City found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/countries/{countryId}/cities", name="_api_list", requirements = {"countryId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"city"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "countryId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="name", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Country $country
     * @return array
     */
    public function getCitiesAction($page, $limit, $orderBy, $orderDir, Country $country){
        return $this->cityManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     * Get One City resource
     *
     * @ApiDoc(
     *     section="Countries.City",
     *     resource=false,
     *     authentication=true,
     *     description="Get One City resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when City found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/countries/{countryId}/cities/{id}", name="_api_show", requirements = {"id"="\d+", "countryId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"city"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "countryId"})
     * @param Country $country
     * @param City $city
     * @return \FOS\RestBundle\View\View|City
     */
    public function getCityAction(Country $country, City $city){
        $checkIsExist = $this->cityManager->findCityByCountry($country, $city);
        if ($checkIsExist === false){
            return $this->view('Not Found Country reference', Response::HTTP_BAD_REQUEST);
        }
        return $city;
    }

    /**
     * Create a new City Resource
     * @ApiDoc(
     *     section="Countries.City",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new City Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="City name"}
     *     },
     *     output={
     *        "class"=City::class,
     *        "groups"={"city"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when City Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/countries/{countryId}/cities", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"city"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "countryId"})
     * @ParamConverter(
     *     "city",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"city_default"} }}
     * )
     * @param Country $country
     * @param City $city
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createCityAction(Country $country, City $city, ConstraintViolationListInterface $validationErrors){
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->cityManager->create($country, $city);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_city_api_show',
                [
                    'countryId' => $data->getCountry()->getId(),
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting City
     * @ApiDoc(
     *     section="Countries.City",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing City Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="City Name"},
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
     * @Rest\Put("/countries/{countryId}/cities/{id}", name="_api_updated", requirements = {"id"="\d+", "countryId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "countryId"})
     * @ParamConverter("city")
     * @ParamConverter(
     *     "cityDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Country $country
     * @param City $city
     * @param CityDTO $cityDTO
     * @return \FOS\RestBundle\View\View|City
     */
    public function updateCityAction(Country $country, City $city, CityDTO $cityDTO ){
        $validator = $this->get('validator');
        $validDTO = $validator->validate($cityDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->cityManager->update($city, $cityDTO);
        $valid = $validator->validate($data, null, 'city_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $city;
    }


    /**
     *
     * Delete an existing City
     * @ApiDoc(
     *     section="Countries.City",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing City Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if City has been successfully deleted",
     *        400="Returned if City does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/countries/{countryId}/cities/{id}", name="_api_delete", requirements = {"id"="\d+", "countryId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "countryId"})
     * @ParamConverter("city")
     * @param Country $country
     * @param City $city
     */
    public function removeCityAction(Country $country, City $city){
         $this->cityManager->delete($city);
    }

}