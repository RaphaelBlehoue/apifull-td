<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Price
 *
 * @ORM\Table(name="prices")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\PriceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Price
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix d'achat")
     * @ORM\Column(name="buy_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $buyPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix de vente")
     * @ORM\Column(name="sell_pirce", type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $sellPirce = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez le Seuil du prix de négociation")
     * @ORM\Column(name="negocite_limit_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $negociteLimitPrice = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="negociate", type="boolean", nullable=true)
     */
    protected $negociate;

    /**
     * @var
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="product", inversedBy="price")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
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
     * Set buyPrice
     *
     * @param string $buyPrice
     *
     * @return Price
     */
    public function setBuyPrice($buyPrice)
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    /**
     * Get buyPrice
     *
     * @return string
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set sellPirce
     *
     * @param string $sellPirce
     *
     * @return Price
     */
    public function setSellPirce($sellPirce)
    {
        $this->sellPirce = $sellPirce;

        return $this;
    }

    /**
     * Get sellPirce
     *
     * @return string
     */
    public function getSellPirce()
    {
        return $this->sellPirce;
    }

    /**
     * Set negociteLimitPrice
     *
     * @param string $negociteLimitPrice
     *
     * @return Price
     */
    public function setNegociteLimitPrice($negociteLimitPrice)
    {
        $this->negociteLimitPrice = $negociteLimitPrice;

        return $this;
    }

    /**
     * Get negociteLimitPrice
     *
     * @return string
     */
    public function getNegociteLimitPrice()
    {
        return $this->negociteLimitPrice;
    }

    /**
     * Set negociate
     *
     * @param boolean $negociate
     *
     * @return Price
     */
    public function setNegociate($negociate)
    {
        $this->negociate = $negociate;

        return $this;
    }

    /**
     * Get negociate
     *
     * @return bool
     */
    public function getNegociate()
    {
        return $this->negociate;
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
     * set created
     *
     * @param  \DateTime $created
     *
     * @return Price
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function saveDate()
    {
       $this->created = new \DateTime('now');
       $this->negociate = false;
    }

    /**
     * Set product
     *
     * @param product $product
     *
     * @return Price
     */
    public function setProduct(product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
