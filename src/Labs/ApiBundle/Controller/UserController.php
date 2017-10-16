<?php

namespace Labs\ApiBundle\Controller;
use FOS\RestBundle\View\View;
use Labs\ApiBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        return [
            'raphael' => 'blehoue',
            'frejus'  => 'bodji'
        ];
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/register", name="register")
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"validator" = {"groups"={"seller_registration", "Default"}}}
     * )
     * @param User $user
     * @return array|User
     */
    public function registerAction(User $user, ConstraintViolationList $validationErrors)
    {
        if (count($validationErrors))
        {
            $Errors = [];
            foreach ($validationErrors as $key => $message) {
               $Errors [][$key] = $message;
            }
        }
        $encoder = $this->get('security.password_encoder');
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        $em = $this->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }
}
