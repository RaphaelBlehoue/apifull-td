<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use libphonenumber\PhoneNumber;
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
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=100,
     *     unique=true,
     *     nullable=true,
     *     options={"comment":"Prend la valeur de l'email"}
     * )
     * @Assert\NotBlank(message="La mise à jour de cette valeur ne peut etre vide")
     * @Assert\Length(
     *     min=4,
     *     max=100,
     *     minMessage="Les caractères minimum pour ce valeur est {{ limit }}",
     *     maxMessage="Les caractères maximum pour ce valeur est {{ limit }}"
     * )
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="S'il vous plait j'ai besoin de votre nom",
     *     groups={"seller_registration"}
     * )
     * @Assert\NotBlank(
     *     message="S'il vous plait j'ai besoin de votre nom",
     *     groups={"seller_registration"}
     * )
     */
     protected $firstname;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="S'il vous plait j'ai besoin de votre prenom",
     *     groups={"seller_registration"}
     * )
     * @Assert\NotNull(
     *     message="S'il vous plait j'ai besoin de votre prenom",
     *     groups={"seller_registration"}
     * )
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="Veuillez entrez une Adresse email pour continuer",
     *     groups={"seller_registration"}
     * )
     */
    protected $email;

    /**
     * @var PhoneNumber
     * @AssertPhoneNumber(type="mobile", message="Numero de téléphone incorrect")
     * @Serializer\Type("libphonenumber\PhoneNumber")
     * @ORM\Column(name="phone", type="phone_number", unique=true, nullable=true)
     * @Assert\NotNull(message="Indiquez un numero de téléphone valide sur le quel nous pouvons vous joindre")
     * @Assert\NotBlank(message="L'espace du numero de téléphone est vide")
     */
    protected $phone;

    /**
     * @var int
     *
     * @ORM\Column(
     *     name="code_validation",
     *     type="integer",
     *     length=5, nullable=true,
     *     options={"comment":"Code validation envoyez sur le téléphone et le mail d'un vendeur."}
     * )
     */
    protected $codeValidation;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTimeImmutable")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;


    /**
     * @ORM\Column(
     *     name="is_active",
     *     type="boolean",
     *     nullable=true,
     *     options={"comment":"Mode d'activation du compte en fonction du client"}
     * )
     */
    protected $isActive;

    /**
     * @ORM\Column(type="string", name="password")
     * @Assert\NotBlank(message="le mot de passe est vide")
     * @Assert\NotNull(message="Veuillez renseigner mot de passe")
     * @Assert\Length(
     *     min=4,
     *     max=100,
     *     minMessage="Pour plus de sécurité les caractères minimum pour le mot de passe est de {{ limit }}",
     *     maxMessage=" Caractère maximum du mot de passe {{ limit }}"
     * )
     */
    protected $password;

    /**
     * @var
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $roles;


    protected $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $quotations;

    /**
     * @ORM\ManyToOne(targetEntity="type", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     * @var Type
     */
    protected $type;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quotations = new ArrayCollection();
        $this->created = new \DateTime('now');
        $this->isActive = false;
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
     * Get type
     *
     * @return type
     */
    public function getType()
    {
        return $this->type;
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
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($email)
    {
        $this->username = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return int
     */
    public function getCodeValidation()
    {
        return $this->codeValidation;
    }

    /**
     * @param int $codeValidation
     */
    public function setCodeValidation($codeValidation)
    {
        $this->codeValidation = $codeValidation;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
}
