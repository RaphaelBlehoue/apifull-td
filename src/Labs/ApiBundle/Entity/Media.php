<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
       $this->top = false;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updated_at(){
        $this->updated = new \DateTime();
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads';
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    public function getAssertPath()
    {
        return $this->getUploadDir().'/'.$this->path;
    }

    /**
     * @ORM\PostRemove()
     */
    public function deleteMedia()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->getAssertPath())) {
            unlink($this->getAssertPath());
        }
    }

}
