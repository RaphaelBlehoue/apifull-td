<?php

namespace Labs\ApiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inventory (inventaire du stock)
 *
 * @ORM\Table("inventories")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\InventoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Inventory
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="reference", length=8, options={"comment" : "référence de l'inventaire"})
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="sku", length=8, options={"comment" : "faire référence au produit relié a ce movement"})
     */
    protected $sku;

    /**
     * @var integer
     *
     * @ORM\Column(name="code_movement", type="integer", options={"comment" : "correspond au status du movement du stock"})
     */
    protected $code_movement;

    /**
     * @var string
     *
     * @ORM\Column(name="referrer", type="string", length=225, options={"comment" : "reference à la quelle ce movement est relié"}, nullable=true)
     */
    protected $referrer;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    protected $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="date")
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="inventories")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="Warehouse", inversedBy="inventories")
     */
    protected $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity="Movement", inversedBy="inventories")
     */
    protected $movement;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Inventory
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
     * Set sku
     *
     * @param string $sku
     *
     * @return Inventory
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set codeMovement
     *
     * @param integer $codeMovement
     *
     * @return Inventory
     */
    public function setCodeMovement($codeMovement)
    {
        $this->code_movement = $codeMovement;

        return $this;
    }

    /**
     * Get codeMovement
     *
     * @return integer
     */
    public function getCodeMovement()
    {
        return $this->code_movement;
    }

    /**
     * Set referrer
     *
     * @param string $referrer
     *
     * @return Inventory
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Inventory
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Inventory
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
     * @return Inventory
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
     * Set product
     *
     * @param Product $product
     *
     * @return Inventory
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
     * Set warehouse
     *
     * @param Warehouse $warehouse
     *
     * @return Inventory
     */
    public function setWarehouse(Warehouse $warehouse = null)
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    /**
     * Get warehouse
     *
     * @return Warehouse
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * Set movement
     *
     * @param Movement $movement
     *
     * @return Inventory
     */
    public function setMovement(Movement $movement = null)
    {
        $this->movement = $movement;

        return $this;
    }

    /**
     * Get movement
     *
     * @return Movement
     */
    public function getMovement()
    {
        return $this->movement;
    }
}
