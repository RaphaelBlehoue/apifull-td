<?php

/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 26/01/2018
 * Time: 13:37
 */

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionDTO
{
    /**
     * @var string
     * @Assert\NotNull(message="Le nom de la promotion ne peut être null")
     * @Assert\NotBlank(message="Veuillez entrez le nom de votre promotion")
     * @Type("string")
     */
    protected $name;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Entrez la description de la promotion ")
     * @Assert\NotNull(message="La description est vide")
     * @Type("string")
     */
    protected $content;

    /**
     * @var int
     * @Assert\NotNull(message="Le pourcentage de la promotion ne peut être null")
     * @Assert\NotBlank(message="Veuillez entrez le pourcentage de votre promotion")
     * @Type("integer")
     */
    protected $percent;


    /**
     * Set name.
     *
     * @param string $name
     *
     * @return PromotionDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content.
     *
     * @param string|null $content
     *
     * @return PromotionDTO
     */
    public function setContent($content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set percent.
     *
     * @param int $percent
     *
     * @return PromotionDTO
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent.
     *
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

}