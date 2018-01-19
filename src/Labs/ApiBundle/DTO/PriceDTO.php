<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/01/2018
 * Time: 14:08
 */

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class PriceDTO
{

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix d'achat")
     * @Type("integer")
     */
    protected $buyPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix de vente")
     * @Type("integer")
     */
    protected $sellPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez le Seuil du prix de négociation")
     * @Type("integer")
     */
    protected $negociteLimitPrice = 0;


    /**
     * Set buyPrice
     *
     * @param string $buyPrice
     *
     * @return PriceDTO
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
     * Set sellPrice
     *
     * @param string $sellPrice
     *
     * @return PriceDTO
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return string
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set negociteLimitPrice
     *
     * @param string $negociteLimitPrice
     *
     * @return PriceDTO
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

}