<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Product (information sur les produits)
 *
 * @ORM\Table("products")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\ProductRepository")
 * @UniqueEntity(
 *      fields={"reference", "name"},
 *      message="Cette valeur existe déjà dans votre base de donnée de produit, renommez la pour continuer"
 * )
 */
class Product
{

    /**
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var string $reference
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", nullable=true)
     */
    protected $libelle;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="products")
     */
    protected $section;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
     */
    protected $brand;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Color", inversedBy="products")
     * @ORM\JoinTable(name="products_colors")
     */
    protected $color;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Size", inversedBy="products")
     * @ORM\JoinTable(name="products_sizes")
     */
    protected $size;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->color = new ArrayCollection();
        $this->size = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Product
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }


    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Product
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Product
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
     * Set brand
     *
     * @param Brand $brand
     *
     * @return Product
     */
    public function setBrand(Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }



    /**
     * Set section
     *
     * @param Section $section
     *
     * @return Product
     */
    public function setSection(Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Add color
     *
     * @param Color $color
     *
     * @return Product
     */
    public function addColor(Color $color)
    {
        $this->color[] = $color;

        return $this;
    }

    /**
     * Remove color
     *
     * @param Color $color
     */
    public function removeColor(Color $color)
    {
        $this->color->removeElement($color);
    }

    /**
     * Get color
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add size
     *
     * @param Size $size
     *
     * @return Product
     */
    public function addSize(Size $size)
    {
        $this->size[] = $size;

        return $this;
    }

    /**
     * Remove size
     *
     * @param Size $size
     */
    public function removeSize(Size $size)
    {
        $this->size->removeElement($size);
    }

    /**
     * Get size
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSize()
    {
        return $this->size;
    }
}
