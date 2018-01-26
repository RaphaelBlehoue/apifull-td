<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProduct
 *
 * @ORM\Table(name="orders_products")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\OrderProductRepository")
 */
class OrderProduct
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
     *
     * @ORM\Column(name="quantity", type="integer")
     */
   protected $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="promo_status", type="boolean")
     */
   protected $promoStatus;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderproduct")
     * @ORM\JoinColumn(referencedColumnName="id", name="product_id", onDelete="CASCADE")
     */
   protected $product;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="orderproduct")
     * @ORM\JoinColumn(referencedColumnName="id", name="command_id", onDelete="CASCADE")
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
     * Set promoStatus.
     *
     * @param bool $promoStatus
     *
     * @return OrderProduct
     */
    public function setPromoStatus($promoStatus)
    {
        $this->promoStatus = $promoStatus;

        return $this;
    }

    /**
     * Get promoStatus.
     *
     * @return bool
     */
    public function getPromoStatus()
    {
        return $this->promoStatus;
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
}
