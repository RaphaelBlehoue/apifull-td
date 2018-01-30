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
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\Serializer;
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
     * @var Serializer
     */
    private $serializer;

    /**
     * @DI\InjectParams({
     *     "registry" = @DI\Inject("doctrine"),
     *     "serializer" = @DI\Inject("jms_serializer")
     * })
     * @param ManagerRegistry $registry
     * @param Serializer $serializer
     */
    public function __construct(ManagerRegistry $registry, Serializer $serializer)
    {
        $this->registry = $registry;
        $this->serializer = $serializer;
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
        $product  = $event->getObject();
        $context = $event->getContext();
        if (!$product instanceof Product){
            return;
        }
        $initialStock = $this->getIntialStockLineForProduct($product->getId());
        $promotions   = $this->getPromotionActivedForProduct($product->getId());
        $prices       = $this->getPriceActivedForProduct($product->getId());
        $result = [
            'initialStock' => $initialStock,
            'ActivedPromotion' => $promotions,
            'ActivedPrice' => $prices
        ];
        $metadata = new StaticPropertyMetadata('stdClass', 'result_data', $result);
        $visitor->visitProperty($metadata, $result, $context);
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
            'isNegociate' => $prices->getIsNegociate(),
            'isActived'   => $prices->getIsActived()
        ];
    }

    /**
     * @param $product
     * @return array|bool
     */
    private function getPromotionActivedForProduct($product){
        $promotions =  $this->registry->getRepository(Promotion::class)->getPromotionActivedForProductId($product);
        return ($promotions !== null) ? [
            'id' => $promotions->getId(),
            'name' => $promotions->getName(),
            'percent' => $promotions->getPercent(),
            'isActived' => $promotions->getIsActived(),
            'content' => $promotions->getContent()
        ]: false;
    }

}