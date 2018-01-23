<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 22/01/2018
 * Time: 19:50
 */

namespace Labs\ApiBundle\Controller\Alerte;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\Exception\InvalidArgumentException;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Notification;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Manager\NotificationManager;
use Labs\ApiBundle\Util\UserUtils;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends BaseApiController{

    /**
     * @var NotificationManager
     */
    protected $notificationManager;

    /**
     * @var UserUtils
     */
    protected $userUtils;

    /**
     * NotificationController constructor.
     * @param NotificationManager $notificationManager
     * @param UserUtils $userUtils
     * @DI\InjectParams({
     *     "notificationManager" = @DI\Inject("api.notification_manager"),
     *     "userUtils" = @DI\Inject("api.user_utils")
     * })
     */
    public function __construct(NotificationManager $notificationManager, UserUtils $userUtils)
    {
        $this->notificationManager = $notificationManager;
        $this->userUtils = $userUtils;
    }

    /**
     * Get the list of all Notification for One user
     *
     * @ApiDoc(
     *     section="Users.Notifications",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all Notification Users",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Notification::class,
     *        "groups"={"notifications"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *         200="Return when Notification found Successful",
     *         401="Return when JWT Token Invalid | authentication failure",
     *         400="Returned when errors",
     *         500="Return when Internal Server Error"
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/users/notifications", name="_api_list")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"notifications","products"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="created", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="DESC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @return array
     */
    public function getNotificationsAction($page, $limit, $orderBy, $orderDir){
        $user = $this->userUtils->getCurrentUser();
        if (!$user instanceof User){
            throw new InvalidArgumentException("Le type d'argument passer est invalide");
        }
        return $this->notificationManager
            ->getListWithParams($user)
            ->getList()
            ->order($orderBy, $orderDir)
            ->paginate($page, $limit);
    }


    /**
     * Get One Notification resource detail to User connected
     *
     * @ApiDoc(
     *     section="Users.Notifications",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Notification resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Notification found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found or Errors"
     *     }
     * )
     *
     * @Rest\Get("/users/notifications/{id}", name="_api_show", requirements = {"id"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"notifications"})
     * @ParamConverter("notification", class="LabsApiBundle:Notification")
     * @param Notification $notification
     * @return \FOS\RestBundle\View\View|Notification
     */
    public function getNotificationAction(Notification $notification){
        $user = $this->userUtils->getCurrentUser();
        $checkIsExist = $this->notificationManager->findNotificationByUserAuthenticated($user, $notification);
        if ($checkIsExist === false){
            return $this->NotFound(['Error' => 'Notification this User not found']);
        }
        return $notification;
    }


    /**
     *
     * Partial Update Notification exist Resource, to update statusRead fields
     * @ApiDoc(
     *     section="Users.Notifications",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Notification exist Resource, to update statusRead fields",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="status", "dataType"="boolean", "required"=true, "description"="status fields"}
     *     },
     *     statusCodes={
     *        200=" Return Partial Notification update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/users/notifications/{id}/status", name="_api_notification_user", requirements = {"id" = "\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"notifications"})
     * @ParamConverter("notification", class="LabsApiBundle:Notification")
     * @param Notification $notification
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Notification
     */
    public function updateNotificationStatusReadAction(Notification $notification, Request $request){
        $field = $request->get('status');
        if ($field === null) {
            return $this->NotFound(['message' => 'Field not define']);
        }
        return $this->notificationManager->patch($notification, 'status', $field);
    }

}