<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class BrandDTO
{


    /**
     * @var string
     * @Assert\NotBlank(message="Entrez une marque avant enregistrement")
     * @Assert\NotNull(message="Le champs est null")
    * @Type("string")
    */
    protected $name;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return BrandDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
