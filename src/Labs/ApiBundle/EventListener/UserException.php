<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 10/10/2017
 * Time: 17:01
 */

namespace Labs\ApiBundle\EventListener;


use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Labs\ApiBundle\ApiEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class UserException implements EventSubscriberInterface
{

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'),
     *         array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::SET_PHONE_VALUE_FAILURE => 'onUserException'
        ];
    }

    public function onUserException(GetResponseForExceptionEvent $event)
    {
        dump($event); die;
    }
}