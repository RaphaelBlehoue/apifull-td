<?php

namespace Labs\ApiBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;


class ProductDTO
{


    /**
     * @var string
     *
     * @Assert\NotBlank(message="Entrez le nom de l'article")
     * @Assert\NotNull(message="Ce champs ne peut être vide")
     * @Type("string")
     */
    protected $name;

    /**
     * @var
     * @Type("string")
     */
    protected $length;

    /**
     * @var
     * @Type("string")
     */
    protected $weight;

    /**
     * @var
     * @Type("string")
     */
    protected $pound;

    /**
     * @var
     * @Type("string")
     */
    protected $unit;



    /**
     * @var string
     * @Assert\NotBlank(message="Entrez la description de l'article")
     * @Assert\NotNull(message="La description est vide")
     * @Type("string")
     */
    protected $content;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductDTO
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
     * Set content
     *
     * @param string $content
     *
     * @return ProductDTO
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get $content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set length
     *
     * @param string $length
     *
     * @return ProductDTO
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return ProductDTO
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set pound
     *
     * @param string $pound
     *
     * @return ProductDTO
     */
    public function setPound($pound)
    {
        $this->pound = $pound;

        return $this;
    }

    /**
     * Get pound
     *
     * @return string
     */
    public function getPound()
    {
        return $this->pound;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return ProductDTO
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
}
