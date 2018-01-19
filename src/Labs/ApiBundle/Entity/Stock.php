<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stock
 *
 * @ORM\Table(name="stocks")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\StockRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Stock
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var int
     * @Assert\NotBlank(message="Entrez le stock minimum de l'article", groups={"stock_default"})
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}.",
     *     groups={"stock_default"}
     * )
     * @ORM\Column(name="stock_min", type="integer")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $stockMin;

    /**
     * @var int
     * @Assert\NotBlank(message="Entrez le stock de sécuriré de l'article", groups={"stock_default"})
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}.",
     *     groups={"stock_default"}
     * )
     * @ORM\Column(name="secure_stock", type="integer")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $secureStock;

    /**
     * @var int
     * @Assert\NotBlank(message="Entrez la quantité de l'article", groups={"stock_default"})
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}.",
     *     groups={"stock_default"}
     * )
     * @ORM\Column(name="quantity", type="integer")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $quantity;

    /**
     * @var
     * @ORM\Column(name="type", type="boolean", nullable=false, options={"comment":"1 => entreé, 0=>sortie"})
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $type;

    /**
     * @ORM\Column(name="origin", type="string", length=255, nullable=false)
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $origin;

    /**
     * @var
     * @ORM\Column(name="stock_fn", type="integer")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $stock_fn = 0;
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({"stocks"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"stocks"})
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

    /**
     * @ORM\PrePersist()
     */
    public function saveDate(){
        $this->created = new \DateTime('now');
    }


    /**
     * Set stockFn
     *
     * @param integer $stockFn
     *
     * @return Stock
     */
    public function setStockFn($stockFn)
    {
        $this->stock_fn = $stockFn;

        return $this;
    }

    /**
     * Get stockFn
     *
     * @return integer
     */
    public function getStockFn()
    {
        return $this->stock_fn;
    }
}
