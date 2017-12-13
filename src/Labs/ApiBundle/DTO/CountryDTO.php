<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


class CountryDTO
{

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du pays")
     * @Type("string")
     */
    protected $name;

    /**
     * @var string
     * @Type("string")
     */
    protected $code;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return CountryDTO
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

    /**
     * Set code
     *
     * @param string $code
     *
     * @return CountryDTO
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
