<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 08/12/2017
 * Time: 11:42
 */

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{

    /**
     * @var string
     * @Assert\NotNull(message="Entrez une categorie")
     * @Assert\NotBlank(message="La valeur du champs est vide")
     * @Type("string")
     */
    protected $name;

    /**
     * @var bool
     * @Assert\Type(type="bool", message="Le type de ce champs est invalide")
     * @Type("bool")
     */
    protected $top;


    /**
     * @var bool
     *
     * @Assert\Type(type="bool", message="Le type de ce champs est invalide")
     * @Type("bool")
     */
    protected $online;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CategoryDTO
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
     * Set top
     *
     * @param boolean $top
     *
     * @return CategoryDTO
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return bool
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set online
     *
     * @param boolean $online
     *
     * @return CategoryDTO
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return bool
     */
    public function getOnline()
    {
        return $this->online;
    }
}