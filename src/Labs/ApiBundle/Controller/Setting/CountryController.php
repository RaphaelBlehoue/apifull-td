<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 13/12/2017
 * Time: 13:46
 */

namespace Labs\ApiBundle\Controller\Setting;


use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\DTO\CountryDTO;
use Labs\ApiBundle\Manager\CountryManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CountryController extends BaseApiController
{


    /**
     * @var CountryManager
     */
    protected $countryManager;

    /**
     * CountryController constructor.
     * @param CountryManager $countryManager
     * @DI\InjectParams({
     *     "countryManager" = @DI\Inject("api.country_manager")
     * })
     */
    public function __construct(CountryManager $countryManager)
    {
        $this->countryManager = $countryManager;
    }

    /**
     * Get the list of all Country reference
     *
     * @ApiDoc(
     *     section="Countries",
     *     resource=true,
     *     authentication=true,
     *     description="Get the list of all Country reference",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Country::class,
     *        "groups"={"country_only"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Country found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/countries", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"country_only","country"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="code", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getCountriesAction($page, $limit, $orderBy, $orderDir){
        return $this->countryManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get One Country resource
     *
     * @ApiDoc(
     *     section="Countries",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Country",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Country found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/countries/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"country","country_only"})
     * @ParamConverter("country", class="LabsApiBundle:Country")
     * @param Country $country
     * @return Country
     */
    public function getCountryAction(Country $country){
        return $country;
    }


    /**
     * Create a new Country Resource
     *
     * @ApiDoc(
     *     section="Countries",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Country Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Country Name"},
     *        {"name"="code", "dataType"="string", "required"=true, "description"="Country code"}
     *     },
     *     output={
     *        "class"=Country::class,
     *        "groups"={"country_only"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Country Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/countries", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"country_only"})
     * @ParamConverter(
     *     "country",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"country_default"} }}
     * )
     * @param Country $country
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createCountryAction(Country $country, ConstraintViolationListInterface $validationErrors){
        if (count($validationErrors) > 0 ) {
            return $this->handleError($validationErrors);
        }
        $data = $this->countryManager->create($country);
        return $this->view($data, Response::HTTP_CREATED,[
            'Location' => $this->generateUrl('get_country_api_show',
                [
                    'id' => $data->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     *
     * Update an exiting Country
     * @ApiDoc(
     *     section="Countries",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Country Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Country name"},
     *        {"name"="code", "dataType"="string", "required"=true, "description"="Country code"},
     *     },
     *     statusCodes={
     *        200="Country  update  Resource Successfully",
     *        204="Country  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/countries/{id}", name="_api_updated", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("country", class="LabsApiBundle:Country")
     * @ParamConverter(
     *     "countryDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Country $country
     * @param CountryDTO $countryDTO
     * @return \FOS\RestBundle\View\View|Country
     */
    public function updateCountryAction(Country $country, CountryDTO $countryDTO){
        $validator = $this->get('validator');
        $validDTO = $validator->validate($countryDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->countryManager->update($country, $countryDTO);
        $valid = $validator->validate($data, null, 'country_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $country;
    }


    /**
     *
     * Delete an existing Country
     * @ApiDoc(
     *     section="Countries",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Country Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Country has been successfully deleted",
     *        400="Returned if Country does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/countries/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Country $country
     */
    public function removeCountryAction(Country $country){
        $this->countryManager->delete($country);
    }

}