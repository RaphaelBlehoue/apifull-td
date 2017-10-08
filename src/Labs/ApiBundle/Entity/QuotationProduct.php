<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuotationProduct (relation entre les produits & le devis)
 *
 * @ORM\Table("quotations_products")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\QuotationproductsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class QuotationProduct
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    protected $quantity;

    /**
     * @var bigint
     *
     * @ORM\Column(name="price", type="bigint")
     */
    protected $price;



    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    protected $discount;

    /**
     * @var bigint
     *
     * @ORM\Column(name="amount_tax", type="bigint")
     */
    protected $amount_tax;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable= true, options={"comment" : "information à renseigné si le devis est de type location"})
     */
    protected $duration;

    /**
     * @ORM\ManyToOne(targetEntity="Quotation", inversedBy="quotationproduct")
     * @ORM\JoinColumn(name="quotation_id", referencedColumnName="id")
     */
    protected $quotation;


    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="quotationproduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return QuotationProduct
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
     * Set price
     *
     * @param integer $price
     *
     * @return QuotationProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return QuotationProduct
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set amountTax
     *
     * @param integer $amountTax
     *
     * @return QuotationProduct
     */
    public function setAmountTax($amountTax)
    {
        $this->amount_tax = $amountTax;

        return $this;
    }

    /**
     * Get amountTax
     *
     * @return integer
     */
    public function getAmountTax()
    {
        return $this->amount_tax;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return QuotationProduct
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
     * Set duration
     *
     * @param integer $duration
     *
     * @return QuotationProduct
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set quotation
     *
     * @param Quotation $quotation
     *
     * @return QuotationProduct
     */
    public function setQuotation(Quotation $quotation = null)
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * Get quotation
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        return $this->quotation;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return QuotationProduct
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
}
