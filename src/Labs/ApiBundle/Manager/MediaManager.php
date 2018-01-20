<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/01/2018
 * Time: 23:23
 */

namespace Labs\ApiBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\Filesystem;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Media;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Repository\MediaRepository;
use Liip\ImagineBundle\Controller\ImagineController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class MediaManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.media_manager", public=true)
 */

class MediaManager extends ApiEntityManager
{
    /**
     * @var array
     */
    private static $allowedMimeTypes = ['image/jpeg', 'image/png'];


    /**
     * @var array
     */
    private static $filterMap = ['small_thumb', 'middle_thumb', 'big_thumb'];


    /**
     * @var int
     */
    private static $maxSize = 2000000;

    /**
     * @var MediaRepository
     */
    protected $repo;

    /**
     * @var Filesystem
     */
    private $filesystem;


    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ImagineController
     */
    private $imagineController;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * MediaManager constructor.
     * @param EntityManagerInterface $em
     * @param Filesystem $filesystem
     * @param RequestStack $requestStack
     * @param ImagineController $imagineController
     * @param TokenStorageInterface $tokenStorage
     * @DI\InjectParams({
     *    "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *    "filesystem" = @DI\Inject("gaufrette.custom_uploads_fs_filesystem"),
     *    "requestStack" = @DI\Inject("request_stack"),
     *    "imagineController" = @DI\Inject("liip_imagine.controller"),
     *    "tokenStorage" = @DI\Inject("security.token_storage")
     * })
     */
    public function __construct(EntityManagerInterface $em, Filesystem $filesystem, RequestStack $requestStack, ImagineController $imagineController, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($em);
        $this->filesystem = $filesystem;
        $this->requestStack = $requestStack;
        $this->imagineController = $imagineController;
        $this->tokenStorage = $tokenStorage;
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

    /**
     * @param Product $product
     * @param Media $media
     * @param UploadedFile $file
     * @return Media
     */
    public function create(Product $product, Media $media, $file)
    {
        $filename = $this->UploadManager($file, $product);
        $media->setTypeMedia($file->getClientMimeType());
        $media->setMediaSize($file->getClientSize());
        $media->setProduct($product);
        $media->setPath($filename);
        $this->em->persist($media);
        $this->em->flush();
        return $media;
    }


    public function patch(Media $media, $fieldName, $fieldValue)
    {
        if ($fieldName == 'top') {
            $media->setTop($fieldValue);
        }
        $this->em->merge($media);
        $this->em->flush();
        $resultSuccess = ['success' => true];
        $result = ['media' => $media];
        return array_merge($resultSuccess, $result);//['updated' => true];
    }

    /**
     * @param UploadedFile $file
     * @return array
     * Check file uploaded
     */

    public function validationFile(UploadedFile $file){
        $errors = [];
        // check file extension
        switch ($file){
            case (null === $file || empty($file)) :
                return   $errors [] = ["message" => "Joingnez un fichier"];
            case  (!$file instanceof UploadedFile) :
                return $errors [] = ["message" => "Le Type de fichier est invalide"];
            case (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) :
                return $errors [] = ["message" => "Invalid File MineType"];
            case ($file->getSize() > self::$maxSize) :
                return $errors [] = ["message" => "File Size excede 2M"];
            default:
                return $errors;
        }
    }

    /**
     * @param $product
     * @param $id
     * @return bool
     */
    public function findMediaByProduct($product, $id)
    {
        $data = $this->repo->getMediaByProduct($product, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }

    /**
     * @param UploadedFile $file
     * @param Product $product
     * @return bool|string
     */
    private function UploadManager($file, $product)
    {
        if (!$file instanceof UploadedFile) {
            return false;
        }
        $filename = sprintf('%s_%s_%s_%s_%s.%s', date('Y'), date('m'), date('d'), mktime(0,1,0), uniqid(), $file->getClientOriginalExtension());
        $adapter = $this->filesystem->getAdapter();
        $newFileDir = $this->getDynamicDir($product, $filename);
        $adapter->write($newFileDir, file_get_contents($file->getPathname()));
        $this->createThumb($newFileDir);
        return $newFileDir;
    }

    /**
     * @param $filename
     * @return bool
     */
    private function createThumb($filename){
        foreach (self::$filterMap as $filter) {
            $this->imagineController->filterAction($this->requestStack->getCurrentRequest(), $filename, $filter);
        }
        return true;
    }

    /**
     * @param Product $product
     * @param $filename
     * @return string
     */
    private function getDynamicDir(Product $product, $filename){
        $user = $this->tokenStorage->getToken()->getUser();
        $dir = $product->getStore()->getSlug().DIRECTORY_SEPARATOR.$user->getSlug();
        return $dir.DIRECTORY_SEPARATOR.$filename;
    }

    public function where($options)
    {
        // TODO: Implement where() method.
    }
}