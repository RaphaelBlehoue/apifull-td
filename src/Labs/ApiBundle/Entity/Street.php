<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;


/**
 * Street
 *
 * @ORM\Table(name="street", options={"comment":"entity reference street"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\StreetRepository")
 */
class Street
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"city","street"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du quartier")
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"city","street"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="City", inversedBy="Street")
     * @Serializer\Groups({"street"})
     * @Serializer\Since("0.1")
     */
    protected $city;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Street
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
     * Set city
     *
     * @param  $city
     *
     * @return Street
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return 
     */
    public function getCity()
    {
        return $this->city;
    }
}
