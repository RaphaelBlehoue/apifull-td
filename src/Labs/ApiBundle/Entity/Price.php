<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\PriceRepository")
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="buy_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $buyPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="sell_pirce", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $sellPirce;

    /**
     * @var string
     *
     * @ORM\Column(name="negocite_limit_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $negociteLimitPrice;

    /**
     * @var bool
     *
     * @ORM\Column(name="negociate", type="boolean", nullable=true)
     */
    private $negociate;


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
}

