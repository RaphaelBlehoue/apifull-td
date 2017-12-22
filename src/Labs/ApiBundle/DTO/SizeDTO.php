<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class SizeDTO
{


    /**
     * @var string
     * @Assert\NotBlank(message="Entrez la valeur de la taille", groups={"size_default"})
     * @Assert\NotNull(message="Le champs est vide", groups={"size_default"})
     * @ORM\Column(name="size", type="string", length=6, unique=true)
     * @Type("string")
     */
    protected $size;


    /**
     * Set size
     *
     * @param string $size
     *
     * @return SizeDTO
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

}
