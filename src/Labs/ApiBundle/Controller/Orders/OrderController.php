<?php

/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 25/01/2018
 * Time: 14:21
 */

namespace Labs\ApiBundle\Controller\Orders;


use FOS\RestBundle\Controller\Annotations as Rest;
use Labs\ApiBundle\Controller\BaseApiController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Labs\ApiBundle\Entity\Command;
use Labs\ApiBundle\Entity\OrderProduct;

class OrderController extends BaseApiController
{


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
     * @Rest\Post("/users/orders", name="_api_created")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"orders"})
     * @param Request $request
     */
    public function createOrderAction(Request $request){
        dump($request->request->all());
        die;
    }
}