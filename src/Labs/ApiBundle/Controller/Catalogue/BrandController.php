<?php

namespace Labs\ApiBundle\Controller\Catalogue;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\BrandDTO;
use Labs\ApiBundle\Entity\Brand;
use Labs\ApiBundle\Manager\BrandManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;


/**
 * Class BrandController
 * @package Labs\ApiBundle\Controller\Catalogue
 * @App\RestResult
 */
class BrandController extends BaseApiController
{
    /**
     * @var BrandManager
     */
    protected $brandManager;


    /**
     * BrandController constructor.
     * @param BrandManager $brandManager
     * @DI\InjectParams({
     *     "brandManager" = @DI\Inject("api.brand_manager")
     * })
     */
    public function __construct(BrandManager $brandManager)
    {
        $this->brandManager = $brandManager;
    }

    /**
     * Get the list of all Brand
     *
     * @ApiDoc(
     *     section="Brands",
     *     resource=true,
     *     authentication=true,
     *     description="Get the list of all Brand",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Brand::class,
     *        "groups"={"brands"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Brand found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/brands", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"brands"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="name", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getBrandsAction($page, $limit, $orderBy, $orderDir)
    {
        return $this->brandManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get One Brands resource
     *
     * @ApiDoc(
     *     section="Brands",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Brands",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Brands found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/brands/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"brands"})
     * @ParamConverter("brand", class="LabsApiBundle:Brand")
     * @param Brand $brand
     * @return Brand
     */
    public function getBrandAction(Brand $brand){
        return $brand;
    }


    /**
     * Create a new Brand Resource
     * @ApiDoc(
     *     section="Brands",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Brand Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Name brand"}
     *     },
     *     output={
     *        "class"=Brand::class,
     *        "groups"={"brand"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Brand Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/brands", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"brands"})
     * @ParamConverter(
     *     "brand",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"brand_default"} }}
     * )
     * @param Brand $brand
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createBrandAction(Brand $brand, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0 ) {
            return $this->handleError($validationErrors);
        }
        $data = $this->brandManager->create($brand);
        return $this->view($data, Response::HTTP_CREATED,[
            'Location' => $this->generateUrl('get_brand_api_show',
                [
                    'id' => $brand->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     *
     * Update an exiting Brand
     * @ApiDoc(
     *     section="Brands",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Brand Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Name Brand"}
     *     },
     *     statusCodes={
     *        200="Brand  update  Resource Successfully",
     *        204="Brand  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/brands/{id}", name="_api_updated", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("brand", class="LabsApiBundle:Brand")
     * @ParamConverter(
     *     "BrandDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Brand $brand
     * @param BrandDTO $dto
     * @return \FOS\RestBundle\View\View|Brand
     */
    public function updateBrandAction(Brand $brand, BrandDTO $dto)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($dto);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->brandManager->update($brand, $dto);
        $valid = $validator->validate($data, null, 'brand_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $brand;
    }


    /**
     *
     * Delete an existing Brands
     * @ApiDoc(
     *     section="Brands",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Brand Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Brand has been successfully deleted",
     *        400="Returned if Brand does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/brands/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Brand $brand
     */
    public function removeBrandAction(Brand $brand) {
        $this->brandManager->delete($brand);
    }

}
