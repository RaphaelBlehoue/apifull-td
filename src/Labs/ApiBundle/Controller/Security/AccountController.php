<?php

namespace Labs\ApiBundle\Controller\Security;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\View\View;
use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\User;
use Labs\ApiBundle\Event\UserEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AccountController extends BaseApiController
{

    /**
     * User login with PhoneNumber, return a JWT. The username parameter must be a valid number phone
     *
     * @ApiDoc(
     *     section="Authentication",
     *     resource=false,
     *     description="Toudeal api.users.login | Login with PhoneNumber",
     *     authentication=false,
     *     parameters={
     *        {"name"="username", "dataType"="string", "required"=true, "description"="Numero de téléphone valide avec code pays prefixé (+) exemple +22506060606"},
     *        {"name"="password", "dataType"="Password", "required"=true, "description"="Mot de passe de connexion utilisateur"}
     *     },
     *     input="null",
     *     response={"name"="token", "dataType"="string", "required"=true, "description"="JWT token", "readonly"=true},
     *     statusCodes={
     *        401="Return when User not Unauthorized",
     *        200="Return when User Logged Successfully",
     *        500="Return when Internal Error"
     *     }
     * )
     * @Rest\Post("/login_check")
     */
    public function loginAction(){}

    /**
     * Create new User type Seller with Roles (ROLE_USER and ROLE_SELLER) added
     *
     * @ApiDoc(
     *     section="Registration users",
     *     resource=false,
     *     description="Toudeal api.users.register_seller | Register Seller",
     *     authentication=false,
     *     parameters={
     *        {"name"="phone", "dataType"="string", "required"=true, "description"="Valid phone Number and country Code prefixy, example:+22506060606"},
     *        {"name"="password", "dataType"="Password", "required"=true, "description"="Password User"},
     *        {"name"="email", "dataType"="string", "required"=true, "description"="valid Email Address"},
     *        {"name"="firstname", "dataType"="string", "required"=true, "description"="User firsname"},
     *        {"name"="lastname", "dataType"="string", "required"=true, "description"="User lastname"}
     *     },
     *     statusCodes={
     *        201="Return when User Sign Up Successfully",
     *        500="Return when Internal Error",
     *        400={
     *           "Return Bad request",
     *           "Return also if Validation Errors exist"
     *        },
     *        409="Return when Unique DataBase validation"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceSeller", name="register_seller", options={ "method_prefix" = false })
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
     * Create new User type | Client with Roles (ROLE_USER) added
     *
     * @ApiDoc(
     *     section="Registration users",
     *     resource=false,
     *     description="Toudeal api.users.register_client | Register Client",
     *     authentication=false,
     *     parameters={
     *        {"name"="phone", "dataType"="string", "required"=true, "description"="Valid phone Number and country Code prefixy, example:+22506060606"},
     *        {"name"="password", "dataType"="Password", "required"=true, "description"="Password User"},
     *        {"name"="email", "dataType"="string", "required"=true, "description"="valid Email Address"},
     *     },
     *     statusCodes={
     *        201="Return when User Sign Up Successfully",
     *        500="Return Internal Error",
     *        400={
     *           "Return Bad request",
     *           "Return Validation Errors"
     *        },
     *        409="Return Unique DataBase validation"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceClient", name="register_client", options={ "method_prefix" = false })
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
     * Create new User type | Entreprise with Roles (ROLE_USER and ROLE_COMPAGNY) added
     *
     * @ApiDoc(
     *     section="Registration users",
     *     resource=false,
     *     description="Toudeal api.users.register_compagny | Register Compagny",
     *     authentication=false,
     *     parameters={
     *        {"name"="phone", "dataType"="string", "required"=true, "description"="Valid phone Number and country Code prefixy, example:+22506060606"},
     *        {"name"="password", "dataType"="Password", "required"=true, "description"="Password User"},
     *        {"name"="email", "dataType"="string", "required"=true, "description"="valid Email Address"},
     *        {"name"="firstname", "dataType"="string", "required"=true, "description"="User firsname"},
     *        {"name"="lastname", "dataType"="string", "required"=true, "description"="User lastname"}
     *     },
     *     statusCodes={
     *        201="Return when Sign Up Successfully",
     *        500="Return when Internal Error",
     *        400={
     *           "Return when Bad request",
     *           "Return when Validation Errors"
     *        },
     *        409="Return when Unique DataBase validation"
     *     }
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"registration"})
     * @Rest\Post("/accounts/signin/ServiceCompagny", name="register_compagny", options={ "method_prefix" = false })
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
            return $this->handleError($validationErrors);
        }

        $dispatcher = $this->get('event_dispatcher');
        $event = new UserEvent($user);
        /* set validation Code pour la connexion */
        $dispatcher->dispatch(ApiEvents::SET_VALIDATION_CODE_USER, $event);
        /* set username pour la connexion */
        $dispatcher->dispatch(ApiEvents::API_SET_USERNAME, $event);
        $user->setRoles($roles);
        try {
            $user->__construct();
            $this->getEm()->persist($user);
            $this->getEm()->flush();
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
            return $this->view($data, Response::HTTP_CREATED);

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
            return $this->view($error, Response::HTTP_CONFLICT);

        }catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTrace(),
                'tracestring' => $e->getTraceAsString()
            ];
            return $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}