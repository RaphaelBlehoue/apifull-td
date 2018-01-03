<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/12/2017
 * Time: 20:32
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Media;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Repository\MediaRepository;


/**
 * Class MediaManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.media_manager", public=true)
 */

class MediaManager extends ApiEntityManager
{
    /**
     * @var MediaRepository
     */
    protected $repo;

    /**
     * MediaManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }


    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Media');
        return $this;
    }

    public function getList()
    {
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('m.'.$column, $direction);
        return $this;
    }


    public function create(Product $product, Media $media)
    {
        $media->setProduct($product);
        $this->em->persist($media);
        $this->em->flush();
        return $media;
    }

    /*public function remove(Media $media){
        @unlink($media->getPath());
        $this->delete($media);
    }*/

    /**
     * @param $product
     * @param $id
     * @return bool
     */
    public function findSectionByCategory($product, $id)
    {
        $data = $this->repo->getMediaByProduct($product, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}