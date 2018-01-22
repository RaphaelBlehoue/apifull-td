<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 18/01/2018
 * Time: 16:32
 */

namespace Labs\ApiBundle\Controller\Stock;

use Labs\ApiBundle\Controller\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Entity\Stock;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Labs\ApiBundle\Manager\StockManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class StockController extends BaseApiController
{


    /**
     * @var StockManager
     */
    protected $stockManager;

    public $message = 'Le produit ne dispose d\'aucun stock pour faire une sortie';


    /**
     * StockController constructor.
     * @param StockManager $stockManager
     * @DI\InjectParams({
     *     "stockManager" = @DI\Inject("api.stock_manager")
     * })
     */
    public function __construct(StockManager $stockManager)
    {
        $this->stockManager = $stockManager;
    }


    /**
     * Get the list of all Stock with parameter
     *
     * @ApiDoc(
     *     section="Products.Stocks",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Stocks",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Stock::class,
     *        "groups"={"stocks","products"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Stocks found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/products/{productId}/stocks", name="_api_list", requirements = {"productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"stocks","products"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="created", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="DESC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Product $product
     * @return array
     */
    public function getStocksAction($page, $limit, $orderBy, $orderDir, Product $product){

        return $this->stockManager->getListWithParams($product)->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get One Stock resource
     *
     * @ApiDoc(
     *     section="Products.Stocks",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Stock resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Stock found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/products/{productId}/stocks/{id}", name="_api_show", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"stocks","products"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("stock", class="LabsApiBundle:Stock")
     * @param Product $product
     * @param Stock $stock
     * @return \FOS\RestBundle\View\View|Stock
     */
    public function getStockAction(Product $product, Stock $stock)
    {
        $checkIsExist = $this->stockManager->findProductByStock($product, $stock);
        if ($checkIsExist === false){
            return $this->view('Not Found Product reference', Response::HTTP_BAD_REQUEST);
        }
        return $stock;
    }

    /**
     * Create a new Stock Resource
     * @ApiDoc(
     *     section="Products.Stocks",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Stocks Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="stock_min", "dataType"="integer", "required"=true, "description"="stock minimum"},
     *        {"name"="secure_stock", "dataType"="integer", "required"=true, "description"="stock de sécurité"},
     *        {"name"="quantity", "dataType"="integer", "required"=true, "description"="quantity a renseignez"},
     *        {"name"="type", "dataType"="boolean", "required"=true, "description"="type de mouvement"},
     *        {"name"="origin", "dataType"="string", "required"=true, "description"="origin de mouvement de stock"}
     *     },
     *     output={
     *        "class"=Stock::class,
     *        "groups"={"stocks"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Stock Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/products/{productId}/stocks", name="_api_created", requirements = { "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"stocks"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter(
     *     "stock",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"stock_default"} }}
     * )
     * @param Product $product
     * @param Stock $stock
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createStockAction(Product $product, Stock $stock, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }

        $check = $this->validateStockSystem($stock, $product);

        if (count($check) > 0) {
            return $this->handleValidate($check);
        }
        $data = $this->stockManager->create($product, $stock);
        return $this->view($data, Response::HTTP_CREATED);
    }

    /**
     * @param $stock
     * @param $product
     * @return array
     * Validate Stock action  (Mouvement Type Out stock) in System (Must do Refactoring in future)
     */
    protected function validateStockSystem($stock, $product)
    {
        $options = [];
        if (!$stock instanceof Stock) { return; }
        if (!$product instanceof Product) { return; }
        if ($stock->getType() === false && (int) $stock->getQuantity() > 0) {
            $initialStock = $this->getIntialStock($product);
            if ($initialStock === null || $initialStock == 0) {
                $options = [
                    'errorStatus' => true,
                    'message' => $this->message
                ];
            }
            if ($initialStock > 0) {
                $outCheck = ($initialStock + (-1 * abs($stock->getQuantity())) >= 0 ) ? true : false;
                if ($outCheck === false) {
                    $options = [
                        'errorStatus' => true,
                        'message' => $this->message
                    ];
                }
            }
        }
        return $options;
    }


    /**
     * @param $productId
     * @return int
     * Get this Product Intial stock
     */
    private function getIntialStock($productId){
        $data = $this->getEm()->getRepository(Stock::class)->getLastStockLineBeforeNewPersist($productId);
        return ($data === null ) ? 0 : $data->getStockFn();
    }

}