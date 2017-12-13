<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class CityDTO
{

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom de la ville")
     * @Type("string")
     */
    protected $name;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return CityDTO
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
