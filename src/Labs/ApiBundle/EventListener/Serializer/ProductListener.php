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
use Labs\ApiBundle\Entity\Product;
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

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        $object  = $event->getObject();
        $data = $this->getStockLine($object->getId());
        $visitor->addData('inital_stock', $data);
    }

    /**
     * @param $product
     * @return mixed
     */
    private function getStockLine($product)
    {
       $data = $this->registry->getRepository(Stock::class)->getLastStockLineBeforeNewPersist($product);
       return ($data === null ) ? 0 : $data->getStockFn();
    }

}