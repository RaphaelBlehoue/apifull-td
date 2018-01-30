<?php

/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 25/01/2018
 * Time: 14:21
 */

namespace Labs\ApiBundle\Controller\Orders;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Manager\CommandManager;
use Labs\ApiBundle\Manager\OrderProductManager;
use Labs\ApiBundle\Manager\ProductManager;
use Labs\ApiBundle\Util\UserUtils;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Labs\ApiBundle\Entity\Command;
use Labs\ApiBundle\Entity\OrderProduct;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderController extends BaseApiController
{


    /**
     * @var CommandManager
     */
    private $commandManager;


    /**
     * @var ProductManager
     */
    private $productManager;


    /**
     * @var OrderProductManager
     */
    private $orderProductManager;


    /**
     * OrderController constructor.
     * @param CommandManager $commandManager
     * @param ProductManager $productManager
     * @param OrderProductManager $orderProductManager
     * @DI\InjectParams({
     *     "productManager" = @DI\Inject("api.product_manager"),
     *     "commandManager" = @DI\Inject("api.order_manager"),
     *     "orderProductManager" = @DI\Inject("api.order_product_manager")
     * })
     */
    public function __construct(CommandManager $commandManager, ProductManager $productManager, OrderProductManager $orderProductManager)
    {
         $this->commandManager = $commandManager;
         $this->productManager = $productManager;
         $this->orderProductManager = $orderProductManager;
    }


    /**
     * Get the list of all Orders
     *
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Orders",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Command::class,
     *        "groups"={"orders","users"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Orders found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"created"})
     * @Rest\Get("/users/orders", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","orders","users","orders_product"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="created", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="DESC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getOrdersAction($page, $limit, $orderBy, $orderDir){
        return $this->commandManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }


    /**
     * Get the list of all Orders By User Id
     *
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Orders By User Id",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Command::class,
     *        "groups"={"orders","users"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Orders found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"created"})
     * @Rest\Get("/users/{userId}/orders", name="_api_user_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","orders","users","orders_product"})
     * @ParamConverter("user", class="LabsApiBundle:User", options={"id" = "userId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="created", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="DESC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param User $user
     * @return array
     */
    public function getOrdersUserAction($page, $limit, $orderBy, $orderDir, User $user){
         return $this->commandManager
             ->getListWithParams($user)
             ->order($orderBy, $orderDir)
             ->paginate($page, $limit);
    }


    /**
     * Get One Order resource
     *
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Order resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Order found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/users/{userId}/orders/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"products","orders","users","orders_product"})
     * @ParamConverter("user", class="LabsApiBundle:User", options={"id" = "userId"})
     * @ParamConverter("order", class="LabsApiBundle:Command")
     * @param User $user
     * @param Command $command
     * @return \FOS\RestBundle\View\View|Command
     */
    public function getOrderAction(User $user, Command $command){
        $checkIsExist = $this->commandManager->findUserByOrder($user, $command);
        if ($checkIsExist === false){
            return $this->view('Not Found User for this Command', Response::HTTP_BAD_REQUEST);
        }
        return $command;
    }


    /**
     * Create a new Order Resource
     *
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Orders Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Command::class,
     *        "groups"={"orders"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Order Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/users/{userId}/orders", name="_api_created")
     * @ParamConverter("user", class="LabsApiBundle:User", options={"id" = "userId"})
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"products","orders_product","orders","price_command", "promo_command","users"})
     * @param Request $request
     * @param User $user
     * @return \FOS\RestBundle\View\View
     */
    public function createOrderAction(Request $request, User $user){
        if ($request->request->all() == null ){
            return $this->errors();
        }
        $origin = $request->server->get('HTTP_HOST');
        $order = new Command();
        $dataOrder = $this->commandManager->create($user, $order, $origin);
        $this->createOrderLine($request, $dataOrder);
        return $this->view($dataOrder, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_order_api_show',
                [
                    'userId'   => $dataOrder->getUser()->getId(),
                    'id'       => $dataOrder->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    /**
     *
     * Partial Update status field an exiting Order
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update status field an exiting Order Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="status", "dataType"="boolean", "required"=true, "description"="status Order"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Order  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/users/orders/{id}/status", name="_api_patch_status", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"orders_product","orders","products","users"})
     * @ParamConverter("command", class="LabsApiBundle:Command")
     * @param Command $command
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchOrderAction(Command $command, Request $request){
        return $this->patch($command, $request, 'status');
    }


    /**
     *
     * Delete an existing Orders
     * @ApiDoc(
     *     section="Users.Orders",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Orders Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Orders has been successfully deleted",
     *        400="Returned if Orders does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Delete("/users/orders/{id}", name="_api_delete", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"orders_product","orders","products","users"})
     * @ParamConverter("command")
     * @param Command $command
     */
    public function removeOrderAction(Command $command){
        $this->commandManager->delete($command);
    }


    /**
     * @param Command $command
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View|Command
     */
    private function patch(Command $command, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $error = $this->handleErrorFieldInteger($field, $fieldName);
        if (count($error) > 0){
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        return $this->commandManager->patch($command, $fieldName, $field);
    }


    /**
     * @param $request
     * @param $order
     */
    private function createOrderLine($request, $order){
        if (!$request instanceof Request){
            return;
        }
        foreach ($request->request->all() as $key => $value){
            $product = $this->productManager->getProductBySku($value['sku']);
            $price = $this->productManager->getActivedPrice($product->getId());
            $promotion = $this->productManager->getActivedPromotion($product->getId());
            $orderProduct = new OrderProduct();
            $this->orderProductManager->create($value['qte'], $price, $orderProduct, $product, $order, $promotion);
        }
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function errors (){
        return $this->NotFound(
            [
                'message' => 'Votre commande ne dispose d\'aucun article',
                'ErrorCode'   => '1514',
                'ErrorRef'   => 'order argument'
            ]
        );
    }

}