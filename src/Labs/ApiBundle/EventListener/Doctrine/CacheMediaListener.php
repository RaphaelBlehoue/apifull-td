<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/01/2018
 * Time: 17:40
 */

namespace Labs\ApiBundle\EventListener\Doctrine;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Labs\ApiBundle\Entity\Media;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


class CacheMediaListener
{

    /**
     * @var CacheManager
     */
    protected $cacheManager;


    protected static $filterMap = [
        'small_thumb',
        'middle_thumb',
        'big_thumb'
    ];

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Media) {
            foreach (self::$filterMap as $filter){
                $this->cacheManager->remove(
                    $entity->getPath(),
                    $filter
                );
            }
        }
    }


}