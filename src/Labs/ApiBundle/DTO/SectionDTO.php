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

class sectionDTO
{

    /**
     * @var string
     * @Assert\NotNull(message="Entrez une section")
     * @Assert\NotBlank(message="La valeur du champs est vide")
     * @Type("string")
     */
    protected $name;


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
     * @return sectionDTO
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
     * Set online
     *
     * @param boolean $online
     *
     * @return sectionDTO
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