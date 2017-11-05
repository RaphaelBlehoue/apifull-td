<?php

namespace Labs\ApiBundle\Controller;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\View\View;
use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Event\UserEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AccountController extends Controller
{

    /**
     * @ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="SignUp Seller User",
     *     statusCodes={
     *        401="Ressource validation Error",
     *        201="ressources created",
     *        500="Internal Error",
     *        404="Ressource Not Found"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceSeller", name="register_seller")
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups"={"seller_registration", "registration"}}}
     * )
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return View|AccountController
     * @internal param array $roles
     */
    public function registerSellerAction(User $user, ConstraintViolationListInterface $validationErrors)
    {
        $roles = ['ROLE_USER','ROLE_SELLER'];
        return $this->register($user, $validationErrors, $roles);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="SignUp Client User",
     *     statusCodes={
     *        401="Ressource validation Error",
     *        201="ressources created",
     *        500="Internal Error",
     *        404="Ressource Not Found"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceClient", name="register_client")
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups"={"registration"}}}
     * )
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return View|AccountController
     * @internal param array $roles
     */
    public function registerClientAction(User $user, ConstraintViolationListInterface $validationErrors)
    {
        $roles = ['ROLE_USER'];
        return $this->register($user, $validationErrors, $roles);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="SignUp Compagny User",
     *     statusCodes={
     *        401="Ressource validation Error",
     *        201="ressources created",
     *        500="Internal Error",
     *        404="Ressource Not Found"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceCompagny", name="register_compagny")
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups"={"registration", "registration_compagny"}}}
     * )
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return View|AccountController
     * @internal param array $roles
     */
    public function registerCompagnyAction(User $user, ConstraintViolationListInterface $validationErrors)
    {
        $roles = ['ROLE_USER, ROLE_COMPAGNY'];
        return $this->register($user, $validationErrors, $roles);
    }


    /**
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @param array $roles
     * @return View|static
     */
    private function register(User $user, ConstraintViolationListInterface $validationErrors, array $roles = array())
    {
        if (count($validationErrors))
        {
            return $this->RessourceValidateErrors($validationErrors);
        }

        $dispatcher = $this->get('event_dispatcher');
        $event = new UserEvent($user);
        /* set validation Code pour la connexion */
        $dispatcher->dispatch(ApiEvents::SET_VALIDATION_CODE_USER, $event);
        /* set username pour la connexion */
        $dispatcher->dispatch(ApiEvents::API_SET_USERNAME, $event);
        $user->setRoles($roles);
        try {
            $em = $this->get('doctrine')->getManager();
            $user->__construct();
            $em->persist($user);
            $em->flush();
            $dispatcher->dispatch(ApiEvents::API_SEND_VALIDATION_CODE, $event);
            $data = [
                'message'    => 'Votre compte a été bien créer',
                'etat'       => 'signin',
                'status'     => true,
                'statusCode' => Response::HTTP_CREATED,
                'payload'    => [
                    'user' => $user->getRoles()
                ]
            ];
            return View::create($data, Response::HTTP_CREATED);

        }catch (UniqueConstraintViolationException $e) {

            $error = [
                'ErrorTrace'    => 'Duplicate data in dataBase',
                'ErrorType'     => 'UniqueConstraintViolationException',
                'Error'         => [
                    'message'     => $e->getMessage(),
                    'SqlError'    => $e->getSQLState(),
                    'code'        => 1409,
                    'ErrorCode'   => $e->getErrorCode(),
                ]
            ];
            return View::create($error, Response::HTTP_CONFLICT);

        }catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTrace(),
                'tracestring' => $e->getTraceAsString()
            ];
            return View::create($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     */
    private function RessourceValidateErrors(ConstraintViolationListInterface $validationErrors)
    {
        $message = [
            'status'           => 'failure',
            'exception'        => 'RessourceValidationErrors',
            'message'          => 'Erreur de validation des données',
            'statusCode'       => Response::HTTP_BAD_REQUEST,
            'code'             => 1400
        ];
        $error = [];
        foreach ($validationErrors as $key => $validationError) {
            $error['payload']['errors'][$key] = [
                $validationError->getPropertyPath()  => $validationError->getMessage()
            ];
        }
        $data = array_merge($message, $error);
        return View::create($data, Response::HTTP_BAD_REQUEST);
    }
}