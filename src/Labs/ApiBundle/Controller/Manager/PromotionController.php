<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 26/01/2018
 * Time: 14:20
 */

namespace Labs\ApiBundle\Controller\Manager;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\PromotionDTO;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Entity\Promotion;
use Labs\ApiBundle\Manager\PromotionManager;
use Labs\ApiBundle\Annotation as App;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;


class PromotionController extends BaseApiController
{

    /**
     * @var PromotionManager
     */
    protected $promotionManager;

    /**
     * PromotionController constructor.
     * @param PromotionManager $promotionManager
     * @DI\InjectParams({
     *     "promotionManager" = @DI\Inject("api.promotion_manager")
     * })
     */
    public function __construct(PromotionManager $promotionManager)
    {
        $this->promotionManager = $promotionManager;
    }


    /**
     * Get the list of all Promotions for One Products
     *
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Promotions Products",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Promotion::class,
     *        "groups"={"promotions"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Promotion found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         400="Returned when errors",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/products/{productId}/promotions", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"promotions"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="created", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="DESC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Product $product
     * @return array
     */
    public function getPromotionsAction($page, $limit, $orderBy, $orderDir, Product $product){
        return $this->promotionManager
            ->getListWithParams($product)
            ->getList()
            ->order($orderBy, $orderDir)
            ->paginate($page, $limit);
    }


    /**
     *
     * Get One Promotion resource for Products
     *
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Promotion",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Promotion found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     * @Rest\Get("/products/{productId}/promotions/{id}", name="_api_show", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"promotions"})
     * @ParamConverter("promotion", class="LabsApiBundle:Promotion", options={"id" = "productId"})
     * @ParamConverter("product", class="LabsApiBundle:Product")
     * @param Product $product
     * @param Promotion $promotion
     * @return \FOS\RestBundle\View\View|Promotion
     */
    public function getPromotionAction(Product $product, Promotion $promotion){
         $checkIsExist = $this->promotionManager->findPromoByproduct($product, $promotion);
         if ($checkIsExist === false){
             return $this->view('Not Found Product reference', Response::HTTP_BAD_REQUEST);
         }
         return $promotion;
    }


    /**
     * Create a new Promotion Resource
     *
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Promotion Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="name Promotion"},
     *        {"name"="percent", "dataType"="string", "required"=true, "description"="Percent Promotion"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Content Promotion"}
     *     },
     *     output={
     *        "class"=Promotion::class,
     *        "groups"={"promotions"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Promotion Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/products/{productId}/promotions", name="_api_created", requirements = {"productId"="\d+"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter(
     *     "promotion",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"promotion_default"} }}
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"promotions"})
     * @param Product $product
     * @param Promotion $promotion
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createPromotionAction(Product $product, Promotion $promotion, ConstraintViolationListInterface $validationErrors){
        if (count($validationErrors)) {
            return $this->handleError($validationErrors);
        }
        // Update All Price Status Actived = false this Product
        $allPrice = $this->promotionManager->patchStatusPrice($product);
        if ($allPrice === false){
            return $this->view(['success' => false, 'ErrorMessage' => 'Erreur du système'], Response::HTTP_FORBIDDEN);
        }
        $data = $this->promotionManager->create($product, $promotion);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_promotion_api_show',
                [
                    'productId' => $data->getProduct()->getId(),
                    'id'        => $data->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Promotion for product
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Promotion Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="name Promotion"},
     *        {"name"="percent", "dataType"="string", "required"=true, "description"="Percent Promotion"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Content Promotion"}
     *     },
     *     statusCodes={
     *        200="Promotion  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/products/{productId}/promotions/{id}", name="_api_updated", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"promotions"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("promotion", class="LabsApiBundle:Promotion")
     * @ParamConverter(
     *     "promotionDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Product $product
     * @param Promotion $promotion
     * @param PromotionDTO $promotionDTO
     * @return \FOS\RestBundle\View\View|Promotion
     */
    public function updatePromotionAction(Product $product, Promotion $promotion, PromotionDTO $promotionDTO){
        $validator = $this->get('validator');
        $validDTO = $validator->validate($promotionDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->promotionManager->update($promotion, $promotionDTO);
        $valid = $validator->validate($data, null, 'promotion_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $promotion;
    }


    /**
     *
     * Partial Update Actived field an exiting Price
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Actived field an exiting Price",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="actived", "dataType"="boolean", "required"=true, "description"="Actived fields Promotion"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Price update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/products/{productId}/promotions/{id}/actived", name="_api_patch_actived", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"promotions"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("promotion")
     * @param Product $product
     * @param Promotion $promotion
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Promotion
     */
    public function patchPromotionAction(Product $product, Promotion $promotion, Request $request){
         return $this->patch($promotion, $request, 'active', $product);
    }


    /**
     *
     * Delete an existing Promotion for product
     * @ApiDoc(
     *     section="Products.Promotions",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Prices Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Promotion has been successfully deleted",
     *        400="Returned if Promotion does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/products/{productId}/promotions/{id}", name="_api_delete", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("promotion")
     * @param Product $product
     * @param Promotion $promotion
     * @return array
     */
    public function removePromotionAction(Product $product, Promotion $promotion){
         $this->promotionManager->delete($promotion);
         return ['success' => true];
    }


    /**
     * @param Promotion $promotion
     * @param Request $request
     * @param $fieldName
     * @param $product
     * @return \FOS\RestBundle\View\View|Promotion
     */
    private function patch(Promotion $promotion, Request $request, $fieldName, $product)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorField($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->promotionManager->patch($promotion, $fieldName, $field, $product);
    }
}