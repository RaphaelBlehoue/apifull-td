<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class StreetDTO
{

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du quartier")
     * @Type("string")
     */
    protected $name;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return StreetDTO
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
