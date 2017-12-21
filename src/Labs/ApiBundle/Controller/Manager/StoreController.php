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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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



    public function getStoresAction(){}

    /**
     * @Rest\Get("/users/departments/{departmentId}/streets/{streetId}/stores/{id}", name="_api_show")
     */
    public function getStoreAction(Request $request){
        dump($request); die;
    }


    /**
     *
     * @Rest\Post("/users/departments/{departmentId}/streets/{streetId}/stores", name="_api_created")
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
     * @Rest\Put("/users/departments/{departmentId}/streets/{streetId}/stores/{id}", name="_api_updated")
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

    public function removeStoreAction(){}

}