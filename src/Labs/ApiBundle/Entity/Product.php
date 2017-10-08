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
 * @ORM\HasLifecycleCallbacks()
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
     * @var integer
     * @ORM\Column(name="buying_price", type="bigint", nullable=true, options={"comment" : "Prix d'achat du produits" })
     */
    protected $buying_price;

    /**
     * @var integer
     * @ORM\Column(name="rental_price", type="bigint", nullable=true , options={"comment" : "Prix de location de la machandise" })
     */
    protected $rental_price;

    /**
     * @var integer
     * @ORM\Column(name="cost", type="bigint", nullable=true , options={"comment" : "Cout du produit de type service" })
     */
    protected $cost;

    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float", nullable=true, options={"comment" : "coefficient de multiplication du produit pour la vente" })
     */
    protected $coefficient;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock_alert", type="integer", nullable=true , options={"comment" : "stock minimum du produit" })
     */
    protected $stock_alert;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", nullable=true)
     */
    protected $libelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="integer", nullable=true, options={"comment" : "Type de service : soit produit ou service"})
     * @Assert\NotBlank(message="Faite le choix de la nature du services")
     * @Assert\NotNull(message="Ce champs doit faire l'objet d'une selection")
     */
    protected $type = 0;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;


    /**
     * @ORM\OneToMany(targetEntity="QuotationProduct", mappedBy="product")
     */
    protected $quotationproduct;

    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="products")
     */
    protected $unit;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
     */
    protected $brand;

    /**
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="product", cascade={"remove"})
     */
    protected $inventories;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quotationproduct = new ArrayCollection();
        $this->inventories = new ArrayCollection();
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
     * Set buyingPrice
     *
     * @param integer $buyingPrice
     *
     * @return Product
     */
    public function setBuyingPrice($buyingPrice)
    {
        $this->buying_price = $buyingPrice;

        return $this;
    }

    /**
     * Get buyingPrice
     *
     * @return integer
     */
    public function getBuyingPrice()
    {
        return $this->buying_price;
    }

    /**
     * Set rentalPrice
     *
     * @param integer $rentalPrice
     *
     * @return Product
     */
    public function setRentalPrice($rentalPrice)
    {
        $this->rental_price = $rentalPrice;

        return $this;
    }

    /**
     * Get rentalPrice
     *
     * @return integer
     */
    public function getRentalPrice()
    {
        return $this->rental_price;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     *
     * @return Product
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set coefficient
     *
     * @param float $coefficient
     *
     * @return Product
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set stockAlert
     *
     * @param integer $stockAlert
     *
     * @return Product
     */
    public function setStockAlert($stockAlert)
    {
        $this->stock_alert = $stockAlert;

        return $this;
    }

    /**
     * Get stockAlert
     *
     * @return integer
     */
    public function getStockAlert()
    {
        return $this->stock_alert;
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
     * Set type
     *
     * @param integer $type
     *
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
     * Add quotationproduct
     *
     * @param QuotationProduct $quotationproduct
     *
     * @return Product
     */
    public function addQuotationproduct(QuotationProduct $quotationproduct)
    {
        $this->quotationproduct[] = $quotationproduct;

        return $this;
    }

    /**
     * Remove quotationproduct
     *
     * @param QuotationProduct $quotationproduct
     */
    public function removeQuotationproduct(QuotationProduct $quotationproduct)
    {
        $this->quotationproduct->removeElement($quotationproduct);
    }

    /**
     * Get quotationproduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuotationproduct()
    {
        return $this->quotationproduct;
    }

    /**
     * Set unit
     *
     * @param Unit $unit
     *
     * @return Product
     */
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
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
     * Add inventory
     *
     * @param Inventory $inventory
     *
     * @return Product
     */
    public function addInventory(Inventory $inventory)
    {
        $this->inventories[] = $inventory;

        return $this;
    }

    /**
     * Remove inventory
     *
     * @param Inventory $inventory
     */
    public function removeInventory(Inventory $inventory)
    {
        $this->inventories->removeElement($inventory);
    }

    /**
     * Get inventories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInventories()
    {
        return $this->inventories;
    }
}
