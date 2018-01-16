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
     * @Type("double")
     */
    protected $buyPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix de vente")
     * @Type("double")
     */
    protected $sellPirce = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez le Seuil du prix de négociation")
     * @Type("double")
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
     * Set sellPirce
     *
     * @param string $sellPirce
     *
     * @return PriceDTO
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