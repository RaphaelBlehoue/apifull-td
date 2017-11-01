<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use libphonenumber\PhoneNumber;
use Symfony\Component\Validator\Constraints AS Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"},groups={"registration"} ,message="Cette adresse email existe déja")
 * @UniqueEntity(fields={"phone"}, groups={"registration"} ,message="Le numero de téléphone est déjà utilisé")
 * @UniqueEntity(fields={"profileName"}, groups={"profilpage"} ,message="Ce nom d'utilisation n'est pas disponible")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @Serializer\Groups({"registration"})
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner votre nom", groups={"seller_registration"})
     * @ORM\Column(name="firstname", type="string", length=225, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner votre prénom", groups={"seller_registration"})
     * @ORM\Column(name="lastname", type="string", length=225, nullable=true)
     */
    protected $lastname;


    /**
     * @Assert\NotNull(message="Entrez un mot de passe", groups={"registration"})
     * @ORM\Column(type="string", name="password", length=64)
     */
    protected $password;


    /**
     * @var string
     * @Assert\NotNull(message="Entrez une adresse email", groups={"registration"})
     * @Assert\Email(message="l'adresse email est invalide", groups={"registration"})
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    protected $isActive;

    /**
     * @var
     * @ORM\Column(name="created", type="datetime",nullable=true)
     * @Serializer\Groups({"registration"})
     */
    protected $created;


    /**
     * @var PhoneNumber
     * @AssertPhoneNumber(type="mobile", message="Numero de téléphone incorrect", groups={"registration"})
     * @Serializer\Type("libphonenumber\PhoneNumber")
     * @ORM\Column(name="phone", type="phone_number", unique=true, nullable=true)
     * @Serializer\Groups({"registration"})
     */
    protected $phone;


    /**
     * @var int
     *
     * @ORM\Column(
     *     name="code_validation",
     *     type="integer",
     *     length=5, nullable=true,
     *     options={"comment":"Code validation envoyez sur le téléphone et le mail d'un vendeur"}
     * )
     * @Serializer\Groups({"registration"})
     * @Serializer\SerializedName("codeValidation")
     */
    protected $codeValidation;

    /**
     * @var
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $roles = [];

    /**
     * @Gedmo\Slug(fields={"firstname","codeValidation"}, updatable=true, separator=".")
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var
     * @Assert\NotNull(message="Entrez un nom de profile", groups={"profilpage"})
     * @ORM\Column(nullable=true, unique=true, name="profile_name", length=255)
     */
    protected $profileName;

    /**
     * @Serializer\VirtualProperty()
     * @param string $separation
     * @return null|string
     * @Serializer\Groups({"registration"})
     * @Serializer\SerializedName("userNamed")
     */
    public function getUserNamed($separation = ' ')
    {
        if (null !== $this->getFirstname() || null !== $this->getLastname()) {
            return $this->getFirstname().$separation.$this->getLastname();
        }else {
            return null;
        }
    }

    /**
     * @var
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     * @var Quotation
     */
    protected $quotations;

    /**
     * @ORM\ManyToOne(targetEntity="type", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     * @var Type
     */
    protected $type;


    public function __construct()
    {
        $this->quotations = new ArrayCollection();
        $this->isActive = true;
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * @param Type $type
     *
     * @return User
     */
    public function setType(Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;
        return array_unique($roles);
    }
    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
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

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * Set phone
     *
     * @param PhoneNumber
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return PhoneNumber
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set codeValidation
     *
     * @param integer $codeValidation
     *
     * @return User
     */
    public function setCodeValidation($codeValidation)
    {
        $this->codeValidation = $codeValidation;

        return $this;
    }

    /**
     * Get codeValidation
     *
     * @return integer
     */
    public function getCodeValidation()
    {
        return $this->codeValidation;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles( array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
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
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function updateDate()
    {
        $this->updated = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist()
     */
    public function createdDate()
    {
        $this->created = new \DateTime('now');
        $this->isActive = true;
    }

    /**
     * Set profileName
     *
     * @param string $profileName
     *
     * @return User
     */
    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;

        return $this;
    }

    /**
     * Get profileName
     *
     * @return string
     */
    public function getProfileName()
    {
        return $this->profileName;
    }
}
