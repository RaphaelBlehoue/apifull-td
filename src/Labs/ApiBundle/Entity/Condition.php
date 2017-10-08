<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Condition (condition de vente )
 *
 * @ORM\Table("conditions")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\ConditionRepository")
 */
class Condition
{


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_informations", type="text")
     */
    protected $additional_informations;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="condition")
     */
    protected $quotations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quotations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Condition
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
     * Set additionalInformations
     *
     * @param string $additionalInformations
     *
     * @return Condition
     */
    public function setAdditionalInformations($additionalInformations)
    {
        $this->additional_informations = $additionalInformations;

        return $this;
    }

    /**
     * Get additionalInformations
     *
     * @return string
     */
    public function getAdditionalInformations()
    {
        return $this->additional_informations;
    }

    /**
     * Add quotation
     *
     * @param Quotation $quotation
     *
     * @return Condition
     */
    public function addQuotation(Quotation $quotation)
    {
        $this->quotations[] = $quotation;

        return $this;
    }

    /**
     * Remove quotation
     *
     * @param Quotation $quotation
     */
    public function removeQuotation(Quotation $quotation)
    {
        $this->quotations->removeElement($quotation);
    }

    /**
     * Get quotations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuotations()
    {
        return $this->quotations;
    }
}
