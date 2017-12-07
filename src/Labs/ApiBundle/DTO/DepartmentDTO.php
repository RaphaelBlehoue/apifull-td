<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 04/12/2017
 * Time: 16:07
 */

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class DepartmentDTO
{

    /**
     * Name of Department
     *
     * @var string
     * @Assert\NotNull(message="Entrez un departement")
     * @Assert\NotBlank(message="Vous n'avez pas renseignez de valeur")
     * @Type("string")
     */
    protected $name;

    /**
     * Position in page of Department
     *
     * @var int
     * @Assert\NotNull(message="Entrez la position d'affichage du département")
     * @Type("integer")
     */
    protected $position;

    /**
     * Top status of Department
     *
     * @var bool
     * @Assert\NotNull(message="Status du department invalide (En top ou pas)")
     * @Type("boolean")
     */
    protected $top;

    /**
     * Online status of Department
     *
     * @var bool
     *
     * @Type("boolean")
     * @Assert\NotNull(message="Status du department invalide (En ligne ou hors ligne)")
     */
    protected $online;

    /**
     * colorCode status of Department
     *
     * @var string
     * @Assert\NotNull(message="Entrez le code couleur hexadecimal du departement")
     * @Type("string")
     */
    protected $colorCode;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return DepartmentDTO
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
     * Set position
     *
     * @param integer $position
     *
     * @return DepartmentDTO
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set top
     *
     * @param boolean $top
     *
     * @return DepartmentDTO
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
     * @return DepartmentDTO
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


    /**
     * Set colorCode
     *
     * @param string $colorCode
     *
     * @return DepartmentDTO
     *
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    /**
     * Get colorCode
     *
     * @return string
     */
    public function getColorCode()
    {
        return $this->colorCode;
    }

}