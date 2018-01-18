<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labs\ApiBundle\DBAL\Types\Stock\StockOriginType;
use Labs\ApiBundle\DBAL\Types\Stock\StockTypeType;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Stock
 *
 * @ORM\Table(name="stock_lines")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\StockRepository")
 */
class Stock
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
     * @var int
     * @Assert\NotBlank(message="Entrez le stock minimum de l'article")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}."
     * )
     * @ORM\Column(name="stock_min", type="integer")
     */
    protected $stockMin;

    /**
     * @var int
     * @Assert\NotBlank(message="Entrez le stock de sécuriré de l'article")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}."
     * )
     * @ORM\Column(name="secure_stock", type="integer")
     */
    protected $secureStock;

    /**
     * @var int
     * @Assert\NotBlank(message="Entrez la quantité de l'article")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}."
     * )
     * @ORM\Column(name="quantity", type="integer")
     */
    protected $quantity;

    /**
     * @var
     * @ORM\Column(name="type", type="StockTypeType", nullable=false)
     * @DoctrineAssert\Enum(entity="Labs\ApiBundle\DBAL\Types\Stock\StockTypeType")
     */
    protected $type;

    /**
     * @ORM\Column(name="origin", type="StockOriginType", nullable=false)
     * @DoctrineAssert\Enum(entity="Labs\ApiBundle\DBAL\Types\Stock\StockOriginType")
     */
    protected $origin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set stockMin
     *
     * @param integer $stockMin
     *
     * @return Stock
     */
    public function setStockMin($stockMin)
    {
        $this->stockMin = $stockMin;

        return $this;
    }

    /**
     * Get stockMin
     *
     * @return int
     */
    public function getStockMin()
    {
        return $this->stockMin;
    }

    /**
     * Set secureStock
     *
     * @param integer $secureStock
     *
     * @return Stock
     */
    public function setSecureStock($secureStock)
    {
        $this->secureStock = $secureStock;

        return $this;
    }

    /**
     * Get secureStock
     *
     * @return int
     */
    public function getSecureStock()
    {
        return $this->secureStock;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Stock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Stock
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Stock
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Stock
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
     * Set product
     *
     * @param Product $product
     *
     * @return Stock
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
