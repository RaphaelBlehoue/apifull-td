<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * OrderProduct
 *
 * @Hateoas\Relation(
 *     "order",
 *      embedded = @Hateoas\Embedded("expr(object.getCommand())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getCommand() === null)",
 *          groups={"orders_product","orders"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "product",
 *      embedded = @Hateoas\Embedded("expr(object.getProduct())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getProduct() === null)",
 *          groups={"orders_product","orders"}
 *     )
 * )
 *
 * @ORM\Table(name="orders_products")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\OrderProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OrderProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $quantity;


    /**
     * @var
     * @ORM\Column(name="line_price", type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $linePrice;

    /**
     * @var int
     * @ORM\Column(name="promo_value", type="integer", nullable=true)
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $promoValue;

    /**
     * @var
     * @ORM\Column(name="promo_price_sum", type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $promo_price_sum;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderproduct")
     * @ORM\JoinColumn(referencedColumnName="id", name="product_id", onDelete="CASCADE")
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $product;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="orderproduct")
     * @ORM\JoinColumn(referencedColumnName="id", name="command_id", onDelete="CASCADE")
     * @Serializer\Groups({"orders_product"})
     * @Serializer\Since("0.1")
     */
   protected $command;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return OrderProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    

    /**
     * Set product.
     *
     * @param Product|null $product
     *
     * @return OrderProduct
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set command.
     *
     * @param Command|null $command
     *
     * @return OrderProduct
     */
    public function setCommand(Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command.
     *
     * @return Command|null
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set linePrice.
     *
     * @param string|null $linePrice
     *
     * @return OrderProduct
     */
    public function setLinePrice($linePrice = null)
    {
        $this->linePrice = $linePrice;

        return $this;
    }

    /**
     * Get linePrice.
     *
     * @return string|null
     */
    public function getLinePrice()
    {
        return $this->linePrice;
    }

    /**
     * Set promoValue.
     *
     * @param int|null $promoValue
     *
     * @return OrderProduct
     */
    public function setPromoValue($promoValue = null)
    {
        $this->promoValue = $promoValue;

        return $this;
    }

    /**
     * Get promoValue.
     *
     * @return int|null
     */
    public function getPromoValue()
    {
        return $this->promoValue;
    }

    /**
     * Set promoPriceSum.
     *
     * @param string|null $promoPriceSum
     *
     * @return OrderProduct
     */
    public function setPromoPriceSum($promoPriceSum = null)
    {
        $this->promo_price_sum = $promoPriceSum;

        return $this;
    }

    /**
     * Get promoPriceSum.
     *
     * @return string|null
     */
    public function getPromoPriceSum()
    {
        return $this->promo_price_sum;
    }

    /**
     * @ORM\PrePersist()
     */
    public function calculateSumLine(){
        if ($this->promoValue > 0) {
            return $this->promo_price_sum = ($this->quantity * $this->linePrice) - ($this->quantity * $this->linePrice * ($this->promoValue/100));
        }
        return $this->promo_price_sum = $this->quantity * $this->linePrice;
    }
}
