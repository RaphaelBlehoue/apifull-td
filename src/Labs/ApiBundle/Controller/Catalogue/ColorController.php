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
use Labs\ApiBundle\Entity\Color;
use Labs\ApiBundle\DTO\ColorDTO;
use Labs\ApiBundle\Manager\ColorManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ColorController extends BaseApiController
{
    /**
     * @var ColorManager
     */
    protected $colorManager;

    /**
     * SizeController constructor.
     * @param ColorManager $colorManager
     * @DI\InjectParams({
     *     "colorManager" = @DI\Inject("api.color_manager")
     * })
     */
    public function __construct(ColorManager $colorManager)
    {
         $this->colorManager = $colorManager;
    }

    /**
     *
     * Get the list of all Color
     *
     * @ApiDoc(
     *     section="Colors",
     *     resource=true,
     *     authentication=true,
     *     description="Get the list of all Colors",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Color::class,
     *        "groups"={"colors"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Color found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/colors", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"colors"})
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
    public function getColorsAction($page, $limit, $orderBy, $orderDir){
        return $this->colorManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     *
     * Get One Color resource
     *
     * @ApiDoc(
     *     section="Colors",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Color",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Color found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     * @Rest\Get("/colors/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"colors"})
     * @ParamConverter("color", class="LabsApiBundle:Color")
     * @param Color $color
     * @return Color
     */
    public function getColorAction(Color $color){
         return $color;
    }

    /**
     *
     * Create a new Color Resource
     * @ApiDoc(
     *     section="Colors",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Color Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="color", "dataType"="string", "required"=true, "description"="Valid unique name color"}
     *     },
     *     output={
     *        "class"=Color::class,
     *        "groups"={"colors"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Color Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Post("/colors", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"colors"})
     * @ParamConverter(
     *     "color",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"color_default"} }}
     * )
     * @param Color $color
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createColorAction(Color $color, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->colorManager->create($color);
        return $this->view($data, Response::HTTP_CREATED,[
            'Location' => $this->generateUrl('get_color_api_show',
                [
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Color
     * @ApiDoc(
     *     section="Colors",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Colors Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="color", "dataType"="string", "required"=true, "description"="Valid unique color name"}
     *     },
     *     statusCodes={
     *        200="Colors  update  Resource Successfully",
     *        204="Colors  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/colors/{id}", name="_api_updated", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"colors"})
     * @ParamConverter("color", class="LabsApiBundle:Color")
     * @ParamConverter(
     *     "colorDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Color $color
     * @param ColorDTO $colorDTO
     * @return \FOS\RestBundle\View\View|Color
     */
    public function updatedColorAction(Color $color, ColorDTO $colorDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($colorDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->colorManager->update($color, $colorDTO);
        $valid = $validator->validate($data, null, 'color_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $color;
    }

    /**
     *
     * Delete an existing Color
     * @ApiDoc(
     *     section="Colors",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Color Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Color has been successfully deleted",
     *        400="Returned if Color does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/colors/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Color $color
     */
    public function removeColorAction(Color $color){
        $this->colorManager->delete($color);
    }

}