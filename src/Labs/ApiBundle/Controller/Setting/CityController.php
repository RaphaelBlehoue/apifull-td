<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 13/12/2017
 * Time: 13:46
 */

namespace Labs\ApiBundle\Controller\Setting;


use FOS\RestBundle\Controller\Annotations as Rest;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\Entity\City;
use Labs\ApiBundle\DTO\CityDTO;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CityController extends BaseApiController
{


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
     *
     * @Rest\Get("/countries/{country_id}/cities", name="_api_list", requirements = {"country_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"country","city","street"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "country_id"})
     * @param Country $country
     * @return \FOS\RestBundle\View\View
     */
    public function getCitiesAction(Country $country){
        if (!$country){
            return $this->view('Not Found Country', Response::HTTP_NOT_FOUND);
        }
        return $this->view($country->getCity(), Response::HTTP_OK);
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
     * @Rest\Get("/countries/{country_id}/cities/{id}", name="_api_show", requirements = {"id"="\d+", "country_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"city","street"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "country_id"})
     * @param Country $country
     * @param City $city
     * @return \FOS\RestBundle\View\View
     */
    public function getCityAction(Country $country, City $city){
        $repository = $this->getEm()->getRepository('LabsApiBundle:City');
        $getOneCity = $repository->getOneCityCountry($country, $city);
        if (null === $getOneCity){
            return $this->view('Not Found City', Response::HTTP_NOT_FOUND);
        }
        return $this->view($getOneCity, Response::HTTP_OK);
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
     * @Rest\Post("/countries/{country_id}/cities", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"city"})
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "country_id"})
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
        if (!$country){return $this->view('Not found Country', Response::HTTP_NOT_FOUND);}
        if (count($validationErrors)) {return $this->EntityValidateErrors($validationErrors);}
        $city->setCountry($country);
        $this->getEm()->persist($city);
        $this->getEm()->flush();
        return $this->view($city, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_city_api_show' ,[
                'country_id' => $country->getId(),
                'id' => $city->getId()
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
     * @Rest\Put("/countries/{country_id}/cities/{id}", name="_api_updated", requirements = {"id"="\d+", "country_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "country_id"})
     * @ParamConverter("city")
     * @ParamConverter(
     *     "cityDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Country $country
     * @param City $city
     * @param CityDTO $cityDTO
     * @return \FOS\RestBundle\View\View
     */
    public function updateCityAction(Country $country, City $city, CityDTO $cityDTO ){
        $repository = $this->getEm()->getRepository('LabsApiBundle:City');
        $getPutData = $repository->getOneCityCountry($country, $city);
        if (!$getPutData){
            return $this->view('Not found Resource', Response::HTTP_NOT_FOUND);
        }
        $groups_validation = "country_default";
        return $this->updated($city, $cityDTO, $groups_validation);
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
     * @Rest\Delete("/countries/{country_id}/cities/{id}", name="_api_delete", requirements = {"id"="\d+", "country_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("country", class="LabsApiBundle:Country", options={"id" = "country_id"})
     * @ParamConverter("city")
     * @param Country $country
     * @param City $city
     * @return \FOS\RestBundle\View\View
     */
    public function removeCityAction(Country $country, City $city){
        $repository = $this->getEm()->getRepository('LabsApiBundle:City');
        $data = $repository->getOneCityCountry($country, $city);
        if (!$data){
            return $this->view('Not found Resource City', Response::HTTP_NOT_FOUND);
        }
        $this->getEm()->remove($city);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

}