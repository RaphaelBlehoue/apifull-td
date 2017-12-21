<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 14/12/2017
 * Time: 19:51
 */

namespace Labs\ApiBundle\Controller\Manager;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\Entity\Store;
use Labs\ApiBundle\Entity\Street;
use Labs\ApiBundle\DTO\StoreDTO;
use Labs\ApiBundle\Manager\StoreManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class StoreController
 * @package Labs\ApiBundle\Controller\Manager
 */
class StoreController extends BaseApiController
{

    /**
     * @var StoreManager
     */
    protected $storeManager;


    /**
     * StoreController constructor.
     * @param StoreManager $storeManager
     * @DI\InjectParams({
     *     "storeManager" = @DI\Inject("api.store_manager")
     * })
     */
    public function __construct(StoreManager $storeManager)
    {
        $this->storeManager = $storeManager;
    }


    /**
     *
     * Get the list of all store
     *
     * @ApiDoc(
     *     section="Users.Department.Store",
     *     resource=false,
     *     authentication=true,
     *     description="Get the list of all store",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     output={
     *        "class"=Store::class,
     *        "groups"={"store","store_groups"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Store Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @App\RestResult(paginate=true, sort={"id"})
     * @Rest\Get("/users/departments/{departmentId}/streets/{streetId}/stores", name="_api_list", requirements = {"departmentId"="\d+", "streetId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"stores","store_groups"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter("street", class="LabsApiBundle:Street", options={"id" = "streetId"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="Results on page")
     * @Rest\QueryParam(name="orderBy", default="id", description="Order by")
     * @Rest\QueryParam(name="orderDir", default="ASC", description="Order direction")
     * @param $page
     * @param $limit
     * @param $orderBy
     * @param $orderDir
     * @param Department $department
     * @param Street $street
     * @return array
     */
    public function getStoresAction($page, $limit, $orderBy, $orderDir, Department $department, Street $street){
        return $this->storeManager->getList()->order($orderBy, $orderDir)->paginate($page, $limit);
    }

    /**
     *
     * Get One Store resource
     *
     * @ApiDoc(
     *     section="Users.Department.Store",
     *     resource=false,
     *     authentication=true,
     *     description="Get One Store",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *         200="Return when Store found",
     *         401="Return when Token JWT Invalid authentication",
     *         500="Return when Internal Server Error",
     *         400="Return when Resource Not found"
     *     }
     * )
     *
     * @Rest\Get("/users/departments/{departmentId}/streets/{streetId}/stores/{id}", name="_api_show", requirements = {"id"="\d+", "departmentId"="\d+", "streetId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"stores","store_groups"})
     * @ParamConverter("department", class="LabsApiBundle:Department", options={"id" = "departmentId"})
     * @ParamConverter("street", class="LabsApiBundle:Street", options={"id" = "streetId"})
     * @ParamConverter("store", class="LabsApiBundle:Store")
     * @param Department $department
     * @param Street $street
     * @param Store $store
     * @return \FOS\RestBundle\View\View|Store
     */
    public function getStoreAction(Department $department, Street $street, Store $store)
    {
        $checkIsExist = $this->storeManager->findDepartmentStreetByStore($department, $street, $store);
        if ($checkIsExist === false){
            return $this->view('Not Found Street or Department reference', Response::HTTP_BAD_REQUEST);
        }
        return $store;
    }


    /**
     * Create a new Store Resource
     *
     * @ApiDoc(
     *     section="Users.Department.Store",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Store Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Store name"},
     *        {"name"="phone", "dataType"="string", "required"=true, "description"="Phone Number"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Description Store"}
     *     },
     *     output={
     *        "class"=Store::class,
     *        "groups"={"stores","store_groups"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Store Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Post("/users/departments/{departmentId}/streets/{streetId}/stores", name="_api_created", requirements = {"departmentId"="\d+", "streetId"="\d+"})
     * @ParamConverter("department", converter="doctrine.orm", options={"id" = "departmentId"})
     * @ParamConverter("street", converter="doctrine.orm", options={"id" = "streetId"})
     * @ParamConverter(
     *     "store",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"store_default"} }}
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"stores"})
     * @param Store $store
     * @param Department $department
     * @param Street $street
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     * @internal param StoreInputEntity $storeInput
     */
    public function createStoreAction(Store $store, Department $department, Street $street, ConstraintViolationListInterface $validationErrors){
        if (count($validationErrors)) {
            return $this->handleError($validationErrors);
        }
        $users = $this->get('api.user_utils')->getCurrentUser();
        $data = $this->storeManager->create($store, $department, $street, $users);
        return $this->view($data, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_store_api_show',
                [
                    'departmentId' => $department->getId(),
                    'streetId'     => $street->getId(),
                    'id'           => $store->getId()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     *
     * Update a existing Store Resource
     *
     * @ApiDoc(
     *     section="Users.Department.Store",
     *     resource=false,
     *     authentication=true,
     *     description="Update a existing Store Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="name", "dataType"="string", "required"=true, "description"="Store name"},
     *        {"name"="phone", "dataType"="string", "required"=true, "description"="Phone Number"},
     *        {"name"="content", "dataType"="text", "required"=true, "description"="Description Store"}
     *     },
     *     output={
     *        "class"=Store::class,
     *        "groups"={"stores","store_groups"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Store Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     *
     * @Rest\Put("/users/departments/{departmentId}/streets/{streetId}/stores/{id}", name="_api_updated", requirements = {"id"="\d+", "departmentId"="\d+", "streetId"="\d+"})
     * @ParamConverter("department", converter="doctrine.orm", options={"id" = "departmentId"})
     * @ParamConverter("street", converter="doctrine.orm", options={"id" = "streetId"})
     * @ParamConverter("store")
     * @ParamConverter("storeDTO", converter="fos_rest.request_body")
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"stores"})
     * @param Store $store
     * @param StoreDTO $storeDTO
     * @return \FOS\RestBundle\View\View
     */
    public function updateStoreAction(Store $store, StoreDTO $storeDTO, Department $department, Street $street)
    {
        $validator = $this->get('validator');
        $validDTO = $validator->validate($storeDTO);
        if (count($validDTO) > 0){
            return $this->handleError($validDTO);
        }
        $data = $this->storeManager->update($store, $storeDTO);
        $valid = $validator->validate($data, null, 'store_default');
        if (count($valid) > 0){
            return $this->handleError($valid);
        }
        $this->getEm()->flush();
        return $this->view($store, Response::HTTP_OK);
    }

    /**
     *
     * Delete an existing Store
     *
     * @ApiDoc(
     *     section="Users.Department.Store",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Store Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Store has been successfully deleted",
     *        400="Returned if Store does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Delete("/users/departments/{departmentId}/streets/{streetId}/stores/{id}", name="_api_delete",requirements = {"id"="\d+", "departmentId"="\d+", "streetId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @param Department $department
     * @param Street $street
     * @param Store $store
     */
    public function removeStoreAction(Department $department, Street $street, Store $store)
    {
        $this->storeManager->delete($store);
    }

}