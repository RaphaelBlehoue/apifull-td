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
use Labs\ApiBundle\DTO\StreetDTO;
use Labs\ApiBundle\Entity\City;
use Labs\ApiBundle\Entity\Street;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class StreetController extends BaseApiController
{


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
     *
     * @Rest\Get("/cities/{city_id}/streets", name="_api_list", requirements = {"city_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"city","street"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "city_id"})
     * @param City $city
     * @return \FOS\RestBundle\View\View
     */
    public function getStreetsAction(City $city){
        if (null === $city){
            return $this->view('Not found City Resource', Response::HTTP_NOT_FOUND);
        }
        return $this->view($city->getStreet(), Response::HTTP_OK);
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
     * @Rest\Get("/cities/{city_id}/streets/{id}", name="_api_show", requirements = {"id"="\d+", "city_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"street","city"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "city_id"})
     * @param City $city
     * @param Street $street
     * @return \FOS\RestBundle\View\View
     */
    public function getStreetAction(City $city, Street $street){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Street');
        $data = $repository->findOneBy([
            'city' => $city,
            'id'   => $street
        ]);
        if (null === $data){
            return $this->view('Not Found Street', Response::HTTP_NOT_FOUND);
        }
        return $this->view($data, Response::HTTP_OK);
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
     * @Rest\Post("/cities/{city_id}/streets", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"street"})
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "city_id"})
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
    public function createStreetAction(City $city, Street $street, ConstraintViolationListInterface $validationErrors){

        if (null === $city){return $this->view('Not found City', Response::HTTP_NOT_FOUND);}
        if (count($validationErrors)) {return $this->EntityValidateErrors($validationErrors);}
        $street->setCity($city);
        $this->getEm()->persist($street);
        $this->getEm()->flush();
        return $this->view($street, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_street_api_show' ,[
                'city_id' => $city->getId(),
                'id' => $street->getId()
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
     * @Rest\Put("/cities/{city_id}/streets/{id}", name="_api_updated", requirements = {"id"="\d+", "city_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "city_id"})
     * @ParamConverter("street")
     * @ParamConverter(
     *     "streetDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param City $city
     * @param Street $street
     * @param StreetDTO $streetDTO
     * @return \FOS\RestBundle\View\View
     */
    public function updateStreetAction(City $city, Street $street, StreetDTO $streetDTO){

        $repository = $this->getEm()->getRepository('LabsApiBundle:Street');
        $data = $repository->findOneBy([
            'city' => $city,
            'id'   => $street
        ]);
        if (!$data){
            return $this->view('Not found Resource', Response::HTTP_NOT_FOUND);
        }
        $groups_validation = "street_default";
        return $this->updated($street, $streetDTO, $groups_validation);
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
     * @Rest\Delete("/cities/{city_id}/streets/{id}", name="_api_delete", requirements = {"id"="\d+", "city_id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("city", class="LabsApiBundle:City", options={"id" = "city_id"})
     * @ParamConverter("street")
     * @param City $city
     * @param Street $street
     * @return \FOS\RestBundle\View\View
     */
    public function removeStreetAction(City $city, Street $street){
        $repository = $this->getEm()->getRepository('LabsApiBundle:Street');
        $data = $repository->findOneBy([
            'city' => $city,
            'id'   => $street
        ]);
        if (!$data){
            return $this->view('Not found Resource City', Response::HTTP_NOT_FOUND);
        }
        $this->getEm()->remove($street);
        $this->getEm()->flush();
        return $this->view('', Response::HTTP_NO_CONTENT);
    }
}