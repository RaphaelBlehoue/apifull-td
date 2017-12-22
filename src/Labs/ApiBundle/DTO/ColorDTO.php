<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class ColorDTO
{

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez la valeur de la couleur")
     * @Assert\NotNull(message="Le champs est vide")
     * @Type("string")
     */
    protected $color;



    /**
     * Set color
     *
     * @param string $color
     *
     * @return ColorDTO
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

}
