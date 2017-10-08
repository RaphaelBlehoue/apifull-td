<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;


/**
 * User (users utilisant le système)
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email","phone"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez renseigner mot de passe")
     */
    protected $password;


    protected $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="user")
     */
    protected $quotations;

    /**
     * @ORM\ManyToOne(targetEntity="type", inversedBy="users")
     * @var Type
     */
    protected $type;

    /**
     * Constructor
     */
    public function __construct($username)
    {
        $this->quotations = new ArrayCollection();
        $this->isActive = true;
        $this->username = $username;
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add quotation
     *
     * @param Quotation $quotation
     *
     * @return User
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

    /**
     * Set type
     *
     * @param type $type
     *
     * @return User
     */
    public function setType(type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
   /* public function PersistPhoneNumberCanonical()
    {
        $phone = $this->getPhone();
        return $this->setPhoneCanonical($phone->getNationalNumber());
    }*/

    /**
     * Get type
     *
     * @return type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return [];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getUsername()
    {
        return $this->username;
    }

}
