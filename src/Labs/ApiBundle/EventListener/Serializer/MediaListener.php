<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 08/01/2018
 * Time: 23:28
 */

namespace Labs\ApiBundle\EventListener\Serializer;


use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Labs\ApiBundle\Entity\Media;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


/**
 * Class MediaListener
 * @package Labs\ApiBundle\EventListener\Serializer
 * @DI\Service("api.media_serializer_listener")
 * @DI\Tag("jms_serializer.event_subscriber")
 */
class MediaListener implements EventSubscriberInterface
{


    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var array
     */
    protected static $filterMap = [
        'small_thumb',
        'middle_thumb',
        'big_thumb'
    ];

    /**
     * MediaListener constructor.
     * @param CacheManager $cacheManager
     * @DI\InjectParams({
     *     "cacheManager" = @DI\Inject("liip_imagine.cache.manager")
     * })
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }


    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Media::class]
        ];
    }

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        $object  = $event->getObject();
        $data = $this->getThumbCache($object->getPath());
        $visitor->addData('thumbs_media', $data);
    }


    /**
     * @param $ImagePath
     * @return array
     */
    private function getThumbCache($ImagePath)
    {
        $thumb_array = [];
        foreach (self::$filterMap as $filter){
            $thumb_array[][$filter] = $this->cacheManager->getBrowserPath($ImagePath, $filter);
        }
        return $thumb_array;
    }

}