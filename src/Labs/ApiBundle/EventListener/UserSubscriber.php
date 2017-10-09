<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 14:19
 */

namespace Labs\ApiBundle\EventListener;


use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\ConfigurationUserRoles;
use Labs\ApiBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::SET_AUTO_USER_ROLE => 'setUserRole'
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function setUserRole(UserEvent $event)
    {
        $getRole[] = ConfigurationUserRoles::UserRole;
        $userEntity = $event->getUser();
        $request = $event->getRequest();
        $formParameterNamed = $event->getFormParameterNamed();

        //check if $formParameterNamed is null
        if (!$formParameterNamed) {
            return;
        }

        // form parameter value
        $formParameterValue = $request->request->get($formParameterNamed);

        foreach ($getRole[0] as $key => $value) {
            if ($key === $formParameterValue) {
                $userEntity->setRoles($value);
            }
        }
    }
}