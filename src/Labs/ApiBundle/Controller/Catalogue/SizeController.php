<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 22/12/2017
 * Time: 10:54
 */

namespace Labs\ApiBundle\Controller\Catalogue;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Size;
use Labs\ApiBundle\DTO\SizeDTO;
use Labs\ApiBundle\Manager\SizeManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SizeController extends BaseApiController
{
    /**
     * @var SizeManager
     */
    protected $sizeManager;

    /**
     * SizeController constructor.
     * @param SizeManager $sizeManager
     * @DI\InjectParams({
     *     "sizeManager" = @DI\Inject("api.size_manager")
     * })
     */
    public function __construct(SizeManager $sizeManager)
    {
         $this->sizeManager = $sizeManager;
    }

    /**
     *
     * Get the list of all Size
     *
     * @ApiDoc(
     *     section="Sizes",
     *     resource=true,
     *     authentication=true,
     *     description="Get the list of all Sizes",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Size::class,
     *        "groups"={"sizes"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Size found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/sizes", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"sizes"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="id", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getSizesAction($page, $limit, $orderBy, $orderDir){
        return $this->sizeManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     *
     * Get One Size resource
     *
     * @ApiDoc(
     *     section="Sizes",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Size",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Size found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     * @Rest\Get("/sizes/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"sizes"})
     * @ParamConverter("size", class="LabsApiBundle:Size")
     * @param Size $size
     * @return Size
     */
    public function getSizeAction(Size $size){
         return $size;
    }

    /**
     *
     * Create a new Size Resource
     * @ApiDoc(
     *     section="Sizes",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Size Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="size", "dataType"="string", "required"=true, "description"="Valid unique name size"}
     *     },
     *     output={
     *        "class"=Size::class,
     *        "groups"={"sizes"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Size Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Post("/sizes", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"sizes"})
     * @ParamConverter(
     *     "size",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"size_default"} }}
     * )
     * @param Size $size
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createSizeAction(Size $size, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->sizeManager->create($size);
        return $this->view($data, Response::HTTP_CREATED,[
            'Location' => $this->generateUrl('get_size_api_show',
                [
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Size
     * @ApiDoc(
     *     section="Sizes",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Sizes Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="size", "dataType"="string", "required"=true, "description"="Valid unique size name"}
     *     },
     *     statusCodes={
     *        200="Sizes  update  Resource Successfully",
     *        204="Sizes  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/sizes/{id}", name="_api_updated", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"sizes"})
     * @ParamConverter("size", class="LabsApiBundle:Size")
     * @ParamConverter(
     *     "sizeDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Size $size
     * @param SizeDTO $sizeDTO
     * @return \FOS\RestBundle\View\View|Size
     */
    public function updatedSizeAction(Size $size, SizeDTO $sizeDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($sizeDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->sizeManager->update($size, $sizeDTO);
        $valid = $validator->validate($data, null, 'size_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $size;
    }

    /**
     *
     * Delete an existing Size
     * @ApiDoc(
     *     section="Sizes",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Size Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Size has been successfully deleted",
     *        400="Returned if Size does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/sizes/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Size $size
     */
    public function removeSizeAction(Size $size){
        $this->sizeManager->delete($size);
    }

}