<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/01/2018
 * Time: 23:45
 */

namespace Labs\ApiBundle\Controller\Catalogue;


use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Controller\BaseApiController;
use Labs\ApiBundle\Entity\Media;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Manager\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends BaseApiController
{

    /**
     * @var MediaManager
     */
    private $mediaManager;

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
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"medias","products"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @param Product $product
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function createMediaAction(Product $product, Request $request)
    {
        $file = $request->files->get('path');
        $media = new Media();
        $data = $this->mediaManager->create($product, $media, $file);
        return $this->view($data, Response::HTTP_CREATED);
    }
}