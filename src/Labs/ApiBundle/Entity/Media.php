<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Media
 *
 * @ORM\Table(name="medias")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Media
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $path;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}"
     * )
     */
    private $file;

    /**
     * @var bool
     *
     * @ORM\Column(name="top", type="boolean")
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $top;

    /**
     * @var string
     *
     * @ORM\Column(name="small", type="string", length=255)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $small;

    /**
     * @var string
     *
     * @ORM\Column(name="middle", type="string", length=255)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $middle;

    /**
     * @var string
     *
     * @ORM\Column(name="big", type="string", length=255)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $big;

    /**
     * @var string
     *
     * @ORM\Column(name="type_media", type="string", length=30, nullable=true)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $typeMedia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="media_size", type="string", length=255, nullable=true)
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $mediaSize;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="medias", cascade={"remove"})
     * @Serializer\Groups({"medias"})
     * @Serializer\Since("0.1")
     */
    protected $product;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set top
     *
     * @param boolean $top
     *
     * @return Media
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return bool
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set small
     *
     * @param string $small
     *
     * @return Media
     */
    public function setSmall($small)
    {
        $this->small = $small;

        return $this;
    }

    /**
     * Get small
     *
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * Set middle
     *
     * @param string $middle
     *
     * @return Media
     */
    public function setMiddle($middle)
    {
        $this->middle = $middle;

        return $this;
    }

    /**
     * Get middle
     *
     * @return string
     */
    public function getMiddle()
    {
        return $this->middle;
    }

    /**
     * Set big
     *
     * @param string $big
     *
     * @return Media
     */
    public function setBig($big)
    {
        $this->big = $big;

        return $this;
    }

    /**
     * Get big
     *
     * @return string
     */
    public function getBig()
    {
        return $this->big;
    }

    /**
     * Set typeMedia
     *
     * @param string $typeMedia
     *
     * @return Media
     */
    public function setTypeMedia($typeMedia)
    {
        $this->typeMedia = $typeMedia;

        return $this;
    }

    /**
     * Get typeMedia
     *
     * @return string
     */
    public function getTypeMedia()
    {
        return $this->typeMedia;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Media
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Media
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set mediaSize
     *
     * @param string $mediaSize
     *
     * @return Media
     */
    public function setMediaSize($mediaSize)
    {
        $this->mediaSize = $mediaSize;

        return $this;
    }

    /**
     * Get mediaSize
     *
     * @return string
     */
    public function getMediaSize()
    {
        return $this->mediaSize;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return Media
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @ORM\PrePersist()
     */
    public function created_at(){
       $this->created = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updated_at(){
        $this->updated = new \DateTime();
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|UploadedFile $file |null
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
}
