<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 08/01/2018
 * Time: 23:28
 */

namespace Labs\ApiBundle\EventListener\Serializer;


use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Labs\ApiBundle\Entity\Price;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Entity\Promotion;
use Labs\ApiBundle\Entity\Stock;


/**
 * Class MediaListener
 * @package Labs\ApiBundle\EventListener\Serializer
 * @DI\Service("api.product_serializer_listener")
 * @DI\Tag("jms_serializer.event_subscriber")
 */
class ProductListener implements EventSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @DI\InjectParams({
     *     "registry" = @DI\Inject("doctrine")
     * })
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }


    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Product::class]
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        $object  = $event->getObject();
        $initialStock = $this->getIntialStockLineForProduct($object->getId());
        $promotions   = $this->getPromotionActivedForProduct($object->getId());
        $prices       = $this->getPriceActivedForProduct($object->getId());
        $result = [
            'inital_stock' => $initialStock,
            'promotions'   => $promotions,
            'prices'       => $prices
        ];
        $visitor->addData('products_items',$result);
    }

    /**
     * @param $product
     * @return mixed
     */
    private function getIntialStockLineForProduct($product)
    {
       $data = $this->registry->getRepository(Stock::class)->getLastStockLineBeforeNewPersist($product);
       return ($data === null ) ? 0 : $data->getStockFn();
    }

    /**
     * @param $product
     * @return array
     */
    private function getPriceActivedForProduct($product){
        $prices =  $this->registry->getRepository(Price::class)->getPriceActivedForProductId($product);
        return [
            'id' => $prices->getId(),
            'buyPrice' => $prices->getBuyPrice(),
            'sellPrice' => $prices->getSellPrice(),
            'negociteLimitPrice' => $prices->getNegociteLimitPrice(),
            'negociate' => $prices->getNegociate(),
            'actived'   => $prices->getActived()
        ];
    }

    /**
     * @param $product
     * @return array
     */
    private function getPromotionActivedForProduct($product){
        $promotions =  $this->registry->getRepository(Promotion::class)->getPromotionActivedForProductId($product);
        return [
            'id' => $promotions->getId(),
            'name' => $promotions->getName(),
            'percent' => $promotions->getPercent(),
            'actived' => $promotions->getactived(),
            'content' => $promotions->getContent()
        ];
    }

}