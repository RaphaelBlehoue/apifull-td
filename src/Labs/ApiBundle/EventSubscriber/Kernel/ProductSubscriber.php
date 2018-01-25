<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 25/01/2018
 * Time: 12:09
 */

namespace Labs\ApiBundle\EventSubscriber\Kernel;


use Doctrine\ORM\EntityManagerInterface;
use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Event\ProductEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::API_CREATE_SKU_PRODUCT => 'createSkuProduct'
        ];
    }

    /**
     * @param ProductEvent $event
     */
    public function createSkuProduct(ProductEvent $event)
    {
       $product =  $event->getProduct();
       if ($product instanceof Product) {
           $codeSku = $this->generateSku(12, 16).'ST'.$product->getStore()->getId().'#'.$product->getId();
           $product->setSku($codeSku);
           $this->entityManager->flush();
       }
    }

    /**
     * @param $x
     * @param $y
     * @return string
     */
    private function generateSku($x, $y){
        $length = rand($x,$y);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return strtoupper($randomString);
    }
}