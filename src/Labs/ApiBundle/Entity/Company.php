<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;


/**
 * Company ( information sur l'entreprise qui utilise le système)
 *
 * @ORM\Table("companies")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CompanyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Company
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
     * @ORM\Column(name="country", type="string", length=255)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="bank", type="string", length=255)
     */
    protected $bank;

    /**
     * @var string
     *
     * @ORM\Column(name="taxpayer_account", type="string", length=255, nullable=true)
     */
    protected $taxpayer_account;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_center", type="string", length=255, nullable=true)
     */
    protected $tax_center;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text")
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(name="regime", type="string", length=255)
     */
    protected $regime;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="represent", type="string", length=255)
     */
    protected $represent;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_two", type="string", length=255)
     */
    protected $phoneTwo;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez le nom de la company")
     * @Assert\NotNull(message="Cette valeur ne doit pas être null")
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255)
     */
    protected $fax;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="company")
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
     * Set country
     *
     * @param string $country
     *
     * @return Company
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set bank
     *
     * @param string $bank
     *
     * @return Company
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set taxpayerAccount
     *
     * @param string $taxpayerAccount
     *
     * @return Company
     */
    public function setTaxpayerAccount($taxpayerAccount)
    {
        $this->taxpayer_account = $taxpayerAccount;

        return $this;
    }

    /**
     * Get taxpayerAccount
     *
     * @return string
     */
    public function getTaxpayerAccount()
    {
        return $this->taxpayer_account;
    }

    /**
     * Set taxCenter
     *
     * @param string $taxCenter
     *
     * @return Company
     */
    public function setTaxCenter($taxCenter)
    {
        $this->tax_center = $taxCenter;

        return $this;
    }

    /**
     * Get taxCenter
     *
     * @return string
     */
    public function getTaxCenter()
    {
        return $this->tax_center;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Company
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Company
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
     * Set street
     *
     * @param string $street
     *
     * @return Company
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set regime
     *
     * @param string $regime
     *
     * @return Company
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;

        return $this;
    }

    /**
     * Get regime
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set represent
     *
     * @param string $represent
     *
     * @return Company
     */
    public function setRepresent($represent)
    {
        $this->represent = $represent;

        return $this;
    }

    /**
     * Get represent
     *
     * @return string
     */
    public function getRepresent()
    {
        return $this->represent;
    }

    /**
     * Set phoneTwo
     *
     * @param string $phoneTwo
     *
     * @return Company
     */
    public function setPhoneTwo($phoneTwo)
    {
        $this->phoneTwo = $phoneTwo;

        return $this;
    }

    /**
     * Get phoneTwo
     *
     * @return string
     */
    public function getPhoneTwo()
    {
        return $this->phoneTwo;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Company
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
     * Set email
     *
     * @param string $email
     *
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Company
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Add quotation
     *
     * @param Quotation $quotation
     *
     * @return Company
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
