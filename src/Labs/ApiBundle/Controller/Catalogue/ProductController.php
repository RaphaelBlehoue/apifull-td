<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 26/12/2017
 * Time: 14:04
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Section;
use Labs\ApiBundle\Entity\Store;
use Labs\ApiBundle\Manager\ProductManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Labs\ApiBundle\Annotation as App;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\DTO\ProductDTO;

class ProductController extends BaseApiController
{
    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * ProductController constructor.
     * @param ProductManager $productManager
     * @DI\InjectParams({
     *     "productManager" = @DI\Inject("api.product_manager")
     * })
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }


    /**
     *
     * Get the list of all Product limited
     *
     * @ApiDoc(
     *     section="Sections.Stores.Products",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Product limited",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Product::class,
     *        "groups"={"products"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Product Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/sections/{sectionId}/stores/{storeId}/products", name="_api_list", requirements = {"sectionId"="\d+", "storeId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","section","brands","stores"})
     * @ParamConverter("section", class="LabsApiBundle:Section", options={"id" = "sectionId"})
     * @ParamConverter("store", class="LabsApiBundle:Store", options={"id" = "storeId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="id", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Section $section
     * @param Store $store
     * @param Product $product
     * @return array
     */
    public function getProductsAction($page,$limit,$orderBy,$orderDir, Section $section, Store $store, Product $product){
        return $this->productManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     *
     * Get One Product resource
     *
     * @ApiDoc(
     *     section="Sections.Stores.Products",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Product",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Product found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/sections/{sectionId}/stores/{storeId}/products/{id}", name="_api_show", requirements = {"id"="\d+", "sectionId"="\d+", "storeId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","section","brands","stores"})
     * @ParamConverter("section", class="LabsApiBundle:Section", options={"id" = "sectionId"})
     * @ParamConverter("store", class="LabsApiBundle:Store", options={"id" = "storeId"})
     * @ParamConverter("product", class="LabsApiBundle:Product")
     * @param Section $section
     * @param Store $store
     * @param Product $product
     * @return \FOS\RestBundle\View\View|Product|Store
     */
    public function getProductAction(Section $section, Store $store, Product $product)
    {
        $checkIsExist = $this->productManager->findSectionStoreByProduct($section, $store, $product);
        if ($checkIsExist === false){
            return $this->view('Not Found Section or Store reference', Response::HTTP_BAD_REQUEST);
        }
        return $product;
    }

    /**
     * Create a new Product Resource
     *
     * @ApiDoc(
     *     section="Sections.Stores.Products",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Product Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Product name"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Description Product"},
     *        {"name"="length", "dataType"="string", "required"=false, "description"="Largeur du Product"},
     *        {"name"="weight", "dataType"="string", "required"=false, "description"="Hauteur du Product"},
     *        {"name"="pound", "dataType"="string", "required"=false, "description"="Poids du Product"}
     *     },
     *     output={
     *        "class"=Product::class,
     *        "groups"={"products"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Product Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/sections/{sectionId}/stores/{storeId}/products", name="_api_created", requirements = {"sectionId"="\d+", "storeId"="\d+"})
     * @ParamConverter("section", class="LabsApiBundle:Section", options={"id" = "sectionId"})
     * @ParamConverter("store", class="LabsApiBundle:Store", options={"id" = "storeId"})
     * @ParamConverter(
     *     "product",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"product_default"} }}
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"products","section","brands","stores"})
     * @param Section $section
     * @param Store $store
     * @param Product $product
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function createProductAction(Section $section, Store $store, Product $product, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0){
            return $this->handleError($validationErrors);
        }
        $data = $this->productManager->create($product, $section, $store);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_product_api_show',
                [
                    'sectionId'   => $data->getSection()->getId(),
                    'storeId'     => $data->getStore()->getId(),
                    'id'          => $data->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Update a existing Product Resource
     *
     * @ApiDoc(
     *     section="Sections.Stores.Products",
     *     resource=false,
     *     authentication=true,
     *     description="Update a existing Product Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Product name"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Description Product"},
     *        {"name"="length", "dataType"="string", "required"=false, "description"="Largeur du Product"},
     *        {"name"="weight", "dataType"="string", "required"=false, "description"="Hauteur du Product"},
     *        {"name"="pound", "dataType"="string", "required"=false, "description"="Poids du Product"}
     *     },
     *     output={
     *        "class"=Product::class,
     *        "groups"={"products"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Product Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Put("/sections/{sectionId}/stores/{storeId}/products/{id}", name="_api_updated", requirements = {"id"="\d+", "sectionId"="\d+", "storeId"="\d+"})
     * @ParamConverter("section", class="LabsApiBundle:Section", options={"id" = "sectionId"})
     * @ParamConverter("store", class="LabsApiBundle:Store", options={"id" = "storeId"})
     * @ParamConverter("product")
     * @ParamConverter("productDTO", converter="fos_rest.request_body")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","section","brands","stores"})
     * @param Product $product
     * @param ProductDTO $productDTO
     * @param Section $section
     * @param Store $store
     * @return \FOS\RestBundle\View\View
     */
    public function updateProductAction(Product $product, ProductDTO $productDTO, Section $section, Store $store)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($productDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->productManager->update($product, $productDTO);
        $valid = $validator->validate($data, null, 'product_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $this->view($product, Response::HTTP_OK);
    }

    


    /**
     *
     * Delete an existing Product
     *
     * @ApiDoc(
     *     section="Sections.Stores.Products",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Product Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Product has been successfully deleted",
     *        400="Returned if Product does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Delete("/sections/{sectionId}/stores/{storeId}/products/{id}", name="_api_delete",requirements = {"id"="\d+", "storeId"="\d+", "sectionId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @ParamConverter("section", class="LabsApiBundle:Section", options={"id" = "sectionId"})
     * @ParamConverter("store", class="LabsApiBundle:Store", options={"id" = "storeId"})
     * @param Product $product
     * @param Section $section
     * @param Store $store
     * @return \FOS\RestBundle\View\View
     */
    public function removeProductAction(Product $product, Section $section, Store $store)
    {
        $checkIsExist = $this->productManager->findSectionStoreByProduct($section, $store, $product);
        if ($checkIsExist === false){
            return $this->view('Not Found Section or Store reference', Response::HTTP_BAD_REQUEST);
        }
        $this->productManager->delete($product);
    }


}