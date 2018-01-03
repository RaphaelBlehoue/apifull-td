<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 03/01/2018
 * Time: 09:23
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use Labs\ApiBundle\Controller\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Annotation as App;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Manager\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends BaseApiController
{

    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * MediaController constructor.
     * @param MediaManager $mediaManager
     * @DI\InjectParams({
     *     "mediaManager" = @DI\Inject("api.media_manager")
     * })
     */
    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }


    /**
     * @Rest\Post("/products/{productId}/medias", name="_api_created", requirements = {"productId"="\d+"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     */
    public function createMediaAction(Product $product, Request $request){
        dump($product->getStore());
        dump($request->files->get('file'));
        dump($request->files->all());
        die;
    }

}