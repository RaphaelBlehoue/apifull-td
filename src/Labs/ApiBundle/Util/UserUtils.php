<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/12/2017
 * Time: 10:44
 */

namespace Labs\ApiBundle\Util;


use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserListener
 * @package Labs\ApiBundle\Util
 * @DI\Service("api.user_utils", public=true)
 */
class UserUtils
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * UserUtils constructor.
     *
     * @DI\InjectParams({
     *      "tokenStorage" = @DI\Inject("security.token_storage")
     * })
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return mixed
     */
    public function getCurrentUser()
    {
        $user = $this->tokenStorage->getToken();
        if ($user->getUser() === null || $user->isAuthenticated() === false){
            return false;
        }
        if (!$user->getUser() instanceof User){
            return false;
        }
        return $user->getUser();
    }
}