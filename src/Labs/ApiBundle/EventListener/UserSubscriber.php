<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 14:19
 */

namespace Labs\ApiBundle\EventListener;


use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\ConfigurationUserRoles;
use Labs\ApiBundle\Event\UserEvent;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var PhoneNumberUtil
     */
    private $numberUtil;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(PhoneNumberUtil $numberUtil, EventDispatcherInterface $dispatcher)
    {
        $this->numberUtil = $numberUtil;
        $this->dispatcher = $dispatcher;
    }

    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::SET_AUTO_USER_ROLE => 'setUserRole',
            ApiEvents::SET_PHONE_VALUE    => 'setConvertAndPhoneNumber',
            ApiEvents::SET_VALIDATION_CODE_USER => 'setValidationCodeUser'
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

    /**
     * @param UserEvent $event
     * @throws \Exception
     */
    public function setConvertAndPhoneNumber(UserEvent $event)
    {
        $userEntity = $event->getUser();
        $request = $event->getRequest();
        $phone = $request->request->get('phone');
        $phoneFormat = $this->numberUtil->parse($phone, PhoneNumberUtil::UNKNOWN_REGION);
        $userEntity->setPhone($phoneFormat);
    }

    /**
     * @param UserEvent $event
     */
    public function setValidationCodeUser(UserEvent $event) {
        $userEntity = $event->getUser();
        $request = $event->getRequest();
        if (null !== $request) {
            $userEntity->setCodeValidation($this->RandomNumeric(5));
        }
    }

    /**
     * @param int $length
     * @return string
     */
    private function RandomNumeric($length = 32)
    {
        $randstr = '';
        mt_srand((double) microtime(TRUE) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
        for ($rand = 0; $rand <= $length; $rand++) {
            $random = mt_rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    /**
     * @param int $length
     * @return string
     */
    private function RandomString($length = 32)
    {
        $randstr = '';
        mt_srand((double) microtime(TRUE) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($rand = 0; $rand <= $length; $rand++) {
            $random = mt_rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

}