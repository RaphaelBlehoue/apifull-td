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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * Create a New Media for product
     * @ApiDoc(
     *     section="Products.Medias",
     *     resource=false,
     *     authentication=true,
     *     description="Create a new Media Resource for Product",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="path", "dataType"="file", "required"=true, "description"="File Url"}
     *     },
     *     output={
     *        "class"=Media::class,
     *        "groups"={"medias"},
     *         "parsers"={
     *             "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
     *         }
     *     },
     *     statusCodes={
     *        201="Return when Media Resource Created Successfully",
     *        500="Return when Internal Server Error",
     *        400={
     *           "Return when Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
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
        if (!$file){
            return $this->view(["message" => "Uploadez des fichiers"], Response::HTTP_BAD_REQUEST);
        }
        $errors = $this->mediaManager->validationFile($file);
        if (count($errors) > 0){
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        $media = new Media();
        $data = $this->mediaManager->create($product, $media, $file);
        return $this->view($data, Response::HTTP_CREATED);
    }

    /**
     *
     * Partial Update Top field an exiting Media
     * @ApiDoc(
     *     section="Products.Medias",
     *     resource=false,
     *     authentication=true,
     *     description="Partial Update Top field an existing Media Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     parameters={
     *        {"name"="top", "dataType"="boolean", "required"=true, "description"="Top Media"}
     *     },
     *     statusCodes={
     *        204=" Return Partial Media  update  Resource Successfully",
     *        500=" Return Internal Server Error",
     *        400={
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Patch("/products/{productId}/medias/{id}/top", name="_api_patch_top", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"medias","products"})
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("media")
     * @param Product $product
     * @param Media $media
     * @param Request $request
     * @return \FOS\RestBundle\View\View|Media
     */
    public function patchMediaTopAction(Product $product, Media $media, Request $request){
        return $this->patch($media, $request, 'top');
    }



    /**
     *
     * Delete an existing Media
     * @ApiDoc(
     *     section="Products.Medias",
     *     resource=false,
     *     authentication=true,
     *     description="Delete an existing Media Resource",
     *     headers={
     *       { "name"="Authorization", "description"="Bearer JWT token", "required"=true }
     *     },
     *     statusCodes={
     *        201="Returned if Media has been successfully deleted",
     *        400="Returned if Media does not exist",
     *        500="Returned if server error",
     *        400={
     *           "Return Bad request exception",
     *           "Return Validation Resource Errors"
     *        }
     *     }
     * )
     * @Rest\Delete("/products/{productId}/medias/{id}", name="_api_delete", requirements = {"id"="\d+", "productId"="\d+"})
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @ParamConverter("product", class="LabsApiBundle:Product", options={"id" = "productId"})
     * @ParamConverter("media")
     * @param Product $product
     * @param Media $media
     */
    public function removeMediaAction(Product $product, Media $media){
        $this->mediaManager->delete($media);
    }



    /**
     * @param Media $media
     * @param Request $request
     * @param $fieldName
     * @return \FOS\RestBundle\View\View|Media
     */
    private function patch(Media $media, Request $request, $fieldName)
    {
        $field = $request->get($fieldName);
        $errors = $this->handleErrorField($field, $fieldName);
        if (count($errors) > 0){
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        return $this->mediaManager->patch($media, $fieldName, $field);
    }
}