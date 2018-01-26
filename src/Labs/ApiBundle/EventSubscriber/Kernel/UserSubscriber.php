<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 14:19
 */

namespace Labs\ApiBundle\EventSubscriber\Kernel;


use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Event\UserEvent;
use libphonenumber\PhoneNumberFormat;
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
            ApiEvents::SET_VALIDATION_CODE_USER => 'setValidationCodeUser',
            ApiEvents::API_SEND_VALIDATION_CODE => 'SendCodeValidation',
            ApiEvents::API_SET_USERNAME => 'setUsernameValue'
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function setUsernameValue(UserEvent $event) {
        $userEntity = $event->getUser();
        if (null ==! $userEntity->getPhone())
        {
            $phoneUtil = $this->numberUtil->format($userEntity->getPhone(), PhoneNumberFormat::E164);
            $userEntity->setUsername($phoneUtil);
        }

    }

    /**
     * @param UserEvent $event
     */
    public function setValidationCodeUser(UserEvent $event) {
        $userEntity = $event->getUser();
        if (null === $userEntity->getCodeValidation()) {
            $userEntity->setCodeValidation($this->RandomNumeric(4));
        }
    }

    /**
     * @param UserEvent $event
     * @return array
     */
    public function SendCodeValidation(UserEvent $event){
        return array('message api sms with code', $event->getUser()->getCodeValidation());
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