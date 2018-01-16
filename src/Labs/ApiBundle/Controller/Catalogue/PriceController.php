<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/01/2018
 * Time: 15:24
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\DTO\PriceDTO;
use Labs\ApiBundle\Entity\Price;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Manager\PriceManager;
use Labs\ApiBundle\Annotation as App;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PriceController extends BaseApiController
{

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * PriceController constructor.
     * @param PriceManager $priceManager
     * @DI\InjectParams({
     *     "priceManager" = @DI\Inject("api.price_manager")
     * })
     */
    public function __construct(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
    }



    /**
     * Get the list of all Prices for Product
     *
     * @ApiDoc(
     *     section="Products.Prices",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Prices with product relationship",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Price::class,
     *        "groups"={"prices"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Prices found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/products/{productId}/prices", name="_api_list", requirements = {"productId" = "\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","prices"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="id", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Product $product
     * @return array
     */
    public function getPricesAction($page, $limit, $orderBy, $orderDir, Product $product)
    {
        return $this->priceManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     * Get One Prices resource
     *
     * @ApiDoc(
     *     section="Products.Prices",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Prices resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Prices found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/products/{productId}/prices/{id}", name="_api_show", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","prices"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("price", class="LabsApiBundle:Price")
     * @param Product $product
     * @param Price $price
     * @return \Doctrine\Common\Collections\Collection|\FOS\RestBundle\View\View|Price
     */
    public function getPriceAction(Product $product, Price $price){
        $checkIsExist = $this->priceManager->findPriceByproduct($product, $price);
        if ($checkIsExist === false){
            return $this->view('Not Found Product reference', Response::HTTP_BAD_REQUEST);
        }
        return $product->getPrice();
    }


    /**
     * Create a new Price Resource for product
     * @ApiDoc(
     *     section="Products.Prices",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Prices Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="buy_price", "dataType"="integer", "required"=true, "description"="Buy Price"},
     *        {"name"="sell_price", "dataType"="integer", "required"=true, "description"="Selling Price"},
     *        {"name"="negocite_limit_price", "dataType"="integer", "required"=true, "description"="Limit Negociation Price"}
     *     },
     *     output={
     *        "class"=Price::class,
     *        "groups"={"prices"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Category Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/products/{productId}/prices", name="_api_created", requirements = {"productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"prices"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter(
     *     "price",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"price_default"} }}
     * )
     * @param Product $product
     * @param Price $price
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createPriceAction(Product $product, Price $price, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->priceManager->create($product, $price);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'get_price_api_show',
                [
                    'productId' => $data->getProduct()->getId(),
                    'id' => $data->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update an exiting Price for product
     * @ApiDoc(
     *     section="Products.prices",
     *     resource=false,
     *     authentication=true,
     *     description="Update an existing Price Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="buy_price", "dataType"="integer", "required"=true, "description"="Buy Price"},
     *        {"name"="sell_price", "dataType"="integer", "required"=true, "description"="Selling Price"},
     *        {"name"="negocite_limit_price", "dataType"="integer", "required"=true, "description"="Limit Negociation Price"}
     *     },
     *     statusCodes={
     *        200="Price  update  Resource Successfully",
     *        204="Price  update  Resource Successfully",
     *        500="Internal Server Error",
     *        400={
     *           "Bad request exception",
     *           "Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Put("/products/{productId}/prices/{id}", name="_api_updated", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"prices"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("price", class="LabsApiBundle:Price")
     * @ParamConverter(
     *     "priceDTO",
     *     converter="fos_rest.request_body"
     * )
     * @param Product $product
     * @param Price $price
     * @param PriceDTO $priceDTO
     * @return \FOS\RestBundle\View\View|Price
     */
    public function updatePriceAction(Product $product, Price $price, PriceDTO $priceDTO)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($priceDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->priceManager->update($price, $priceDTO);
        $valid = $validator->validate($data, null, 'price_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $price;
    }


    /**
     *
     * Partial Update Online field an exiting Category
     * @ApiDoc(
     *     section="Departments.Category",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Online field an existing Category Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="Online", "dataType"="boolean", "required"=true, "description"="Online Category"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Category  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/products/{productId}/prices/{id}/status", name="_api_patch_status", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"prices"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("price")
     * @param Product $product
     * @param Price $price
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Price
     */
    public function patchPriceNegociationStatusAction(Product $product, Price $price, Request $request)
    {
        return $this->patch($price, $request, 'active');
    }


    /**
     *
     * Delete an existing Price for product
     * @ApiDoc(
     *     section="Products.prices",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Prices Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Price has been successfully deleted",
     *        400="Returned if Price does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/products/{productId}/prices/{id}", name="_api_delete", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("price")
     * @param Product $product
     * @param Price $price
     * @return array
     */
    public function removePriceAction(Product $product, Price $price)
    {
        $this->priceManager->delete($price);
        return ['success' => true];
    }

    /**
     * @param Price $price
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View|Price
     */
    private function patch(Price $price, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorField($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->priceManager->patch($price, $fieldName, $field);
    }

}