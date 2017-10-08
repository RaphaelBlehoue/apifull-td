<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints AS Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Customer (client du système)
 *
 * @ORM\Table("customers")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CustomerRepository")
 */
class Customer
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
     * @Gedmo\Slug(fields={"name"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="delegate", type="string", length=255)
     */
    protected $delegate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;

    /**
     * @Assert\Email(
     *     message = "Cette valeur '{{ value }}' n'est pas un email valide."
     * )
     * @ORM\Column(name="email_company", type="string", length=255, nullable=true)
     */
    protected $email_company;

    /**
     * @Assert\Email(
     *     message = "Cette valeur '{{ value }}' n'est pas un email valide."
     * )
     * @ORM\Column(name="email_delegate", type="string", length=255, nullable=true)
     */
    protected $email_delegate;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="customer")
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
     * @return Customer
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Customer
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set delegate
     *
     * @param string $delegate
     *
     * @return Customer
     */
    public function setDelegate($delegate)
    {
        $this->delegate = $delegate;

        return $this;
    }

    /**
     * Get delegate
     *
     * @return string
     */
    public function getDelegate()
    {
        return $this->delegate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Customer
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set emailCompany
     *
     * @param string $emailCompany
     *
     * @return Customer
     */
    public function setEmailCompany($emailCompany)
    {
        $this->email_company = $emailCompany;

        return $this;
    }

    /**
     * Get emailCompany
     *
     * @return string
     */
    public function getEmailCompany()
    {
        return $this->email_company;
    }

    /**
     * Set emailDelegate
     *
     * @param string $emailDelegate
     *
     * @return Customer
     */
    public function setEmailDelegate($emailDelegate)
    {
        $this->email_delegate = $emailDelegate;

        return $this;
    }

    /**
     * Get emailDelegate
     *
     * @return string
     */
    public function getEmailDelegate()
    {
        return $this->email_delegate;
    }

    /**
     * Add quotation
     *
     * @param Quotation $quotation
     *
     * @return Customer
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
