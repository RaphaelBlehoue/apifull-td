<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 12:35
 */

namespace Labs\ApiBundle\EventListener;


use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Entity\Notification;
use Labs\ApiBundle\Event\StockEvent;
use Labs\ApiBundle\Manager\NotificationManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class StockSubscriber
 * @package Labs\ApiBundle\EventListener
 */
class StockSubscriber implements EventSubscriberInterface
{


    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var NotificationManager
     */
    private $notificationManager;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    public function __construct(RegistryInterface $registry, NotificationManager $notificationManager, TokenStorageInterface $tokenStorage)
    {
        $this->registry = $registry;
        $this->notificationManager = $notificationManager;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::API_SEND_NOTIFICATION_STOCK_ALERT => 'sendNotificationStockAlert'
        ];
    }

    public function sendNotificationStockAlert(StockEvent $event)
    {
        $request  = $event->getRequest();
        $stock    = $event->getStock();
        $user     = $this->tokenStorage->getToken()->getUser();
        $notification = $this->createNotif($request);
        //Create Notification
        $this->notificationManager->create($notification, $user);
    }

    /**
     * @param $request
     * @return Notification
     */
    private function createNotif(Request $request)
    {
        $notification = new Notification();
        $product = $request->get('product');
        $subject = sprintf('Alerte de stock sur votre article: %s de SKU: %s', $product->getName(), $product->getSku());
        $content = sprintf("Le Stock de l'article %s de SKU : %s doit être réapprovisionné", $product->getName(), $product->getSku());
         $notification
            ->setType('Notification')
            ->setSubject($subject)
            ->setContent($content)
            ->setOrigin('Stock')
            ->setActor('Systeme');
        return $notification;
    }
}