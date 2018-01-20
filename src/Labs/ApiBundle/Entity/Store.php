<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints AS Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Store
 *
 * @ORM\Table(name="stores", options={"comment":"entity reference store"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\StoreRepository")
 * @UniqueEntity(fields={"name"},groups={"store_default"} ,message="Ce nom de boutique est déja utilisé")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_store_api_show",
 *          parameters = {
 *              "departmentId" = "expr(object.getDepartment().getId())",
 *              "streetId" = "expr(object.getStreet().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"stores","store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_store_api_created",
 *          parameters = {
 *              "departmentId" = "expr(object.getDepartment().getId())",
 *              "streetId" = "expr(object.getStreet().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"stores","store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_store_api_updated",
 *          parameters = {
 *              "departmentId" = "expr(object.getDepartment().getId())",
 *              "streetId" = "expr(object.getStreet().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"stores","store_groups"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "remove",
 *      href = @Hateoas\Route(
 *          "remove_store_api_delete",
 *          parameters = {
 *              "departmentId" = "expr(object.getDepartment().getId())",
 *              "streetId" = "expr(object.getStreet().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"stores","store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "department",
 *      embedded = @Hateoas\Embedded("expr(object.getDepartment())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getDepartment() === null)",
 *          groups={"store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "street",
 *      embedded = @Hateoas\Embedded("expr(object.getStreet())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getStreet() === null)",
 *          groups={"store_groups"}
 *     )
 * )
 *
 */
class Store
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"stores", "store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Le champs nom de la boutique est vide", groups={"store_default"})
     * @Assert\NotNull(message="Entrez le nom de votre boutique", groups={"store_default"})
     * @Serializer\Groups({"stores", "store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotBlank(message="Le champs description de la boutique est vide", groups={"store_default"})
     * @Assert\NotNull(message="Entrez une description de votre boutique", groups={"store_default"})
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Serializer\Groups({"stores", "store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({"stores", "store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var PhoneNumber
     * @Serializer\Type("libphonenumber\PhoneNumber")
     * @Assert\NotBlank(message="Le champs numero de téléphone de votre boutique est vide", groups={"store_default"})
     * @Assert\NotNull(message="Numero de téléphone de votre boutique", groups={"store_default"})
     * @AssertPhoneNumber(type="mobile", message="Numero de téléphone incorrect", groups={"store_default"})
     * @ORM\Column(name="phone", type="phone_number", nullable=false)
     * @Serializer\Groups({"stores_admin"})
     * @Serializer\Since("0.1")
     */
    protected $phone;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=true, separator=".")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"stores", "store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $slug;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="store")
     * @Serializer\Groups({"stores"})
     * @Serializer\Since("0.1")
     */
    protected $department;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="store")
     * @Serializer\Groups({"stores"})
     * @Serializer\Since("0.1")
     */
    protected $user;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Product", mappedBy="store")
     * @Serializer\Groups({"stores_groups"})
     * @Serializer\Since("0.1")
     */
    protected $products;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Street", inversedBy="store")
     * @Serializer\Groups({"stores"})
     * @Serializer\Since("0.1")
     */
    protected $street;

    public function __construct()
    {
        $this->created = new \DateTime('now');
    }

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
     * @return Store
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
     * Set content
     *
     * @param string $content
     *
     * @return Store
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Store
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
     * @param PhoneNumber $phone
     *
     * @return Store
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Store
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
     * Set department
     *
     * @param Department $department
     *
     * @return Store
     */
    public function setDepartment(Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Store
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set street
     *
     * @param Street $street
     *
     * @return Store
     */
    public function setStreet(Street $street = null)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return Street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setDateCreated(){
        $this->created = new  \DateTime('now');
    }


    /**
     * Add product
     *
     * @param Product $product
     *
     * @return Store
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
