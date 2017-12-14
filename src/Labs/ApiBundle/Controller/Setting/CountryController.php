<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 13/12/2017
 * Time: 13:46
 */

namespace Labs\ApiBundle\Controller\Setting;


use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Country;
use Labs\ApiBundle\DTO\CountryDTO;
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
     *
     * @Rest\Get("/countries", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"country_only","country"})
     */
    public function getCountriesAction(){
        $country = $this->getEm()->getRepository('LabsApiBundle:Country')
            ->findAll();
        return $this->view($country, Response::HTTP_OK);
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
     * @return \FOS\RestBundle\View\View|Country|null|object
     */
    public function getCountryAction(Country $country){
        $data = $this->getEm()->getRepository('LabsApiBundle:Country')
            ->find($country);
        if (null === $data) {
            return $this->view(['message' => 'Country Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->view($data, Response::HTTP_OK);
    }


    /**
     * Create a new Country Resource
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
            $error = $this->get('labs_api.util.resource_validation')->DataValidation($validationErrors);
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        $this->getEm()->persist($country);
        $this->getEm()->flush();
        /** @var Country $country */
        return $this->view($country, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_country_api_show' ,
                ['id' => $country->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
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
     * @return \FOS\RestBundle\View\View
     * @internal param Request $request
     */
    public function updateCountryAction(Country $country, CountryDTO $countryDTO){
        if (!$country){
            return $this->view('Not found Country', Response::HTTP_NOT_FOUND);
        }
        $groups_validation = "country_default";
        return $this->updated($country, $countryDTO, $groups_validation);
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
     * @return \FOS\RestBundle\View\View
     */
    public function removeCountryAction(Country $country){
        if (!$country){
            return $this->view('Not found Country', Response::HTTP_NOT_FOUND);
        }
        $this->getEm()->remove($country);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

}