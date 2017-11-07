<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;



/**
 * Department
 *
 * @ORM\Table(name="departments", options={"comment":"entity reference articles departments"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\DepartmentRepository")
 */
class Department
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"department"})
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez un departement")
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Serializer\Groups({"department"})
     */
    protected $name;

    /**
     * @var int
     * @Assert\NotNull(message="Entrez la position d'affichage du département")
     * @ORM\Column(name="position", type="integer")
     * @Serializer\Groups({"department"})
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="top", type="boolean", nullable=true)
     * @Serializer\Groups({"department"})
     */
    protected $top;

    /**
     * @var bool
     *
     * @ORM\Column(name="online", type="boolean", nullable=true)
     * @Serializer\Groups({"department"})
     */
    protected $online;

    /**
     * @Gedmo\Slug(fields={"name","id"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"department"})
     */
    protected $slug;


    /**
     * @var string
     * @Assert\NotNull(message="Entrez le code couleur hexadecimal du departement, exemple(#FFEBBC)")
     * @ORM\Column(name="color_code", type="string", length=255, nullable=true)
     * @Serializer\Groups({"department"})
     */
    protected $colorCode;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Category", mappedBy="department", cascade={"remove"})
     */
    protected $category;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Store", mappedBy="department")
     */
    protected $store;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->store = new ArrayCollection();
        $this->top = false;
        $this->online = true;
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
     *
     * @return Department
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
     * @return Department
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
     * @return Department
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
     * @return Department
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Department
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
     * Set colorCode
     *
     * @param string $colorCode
     *
     * @return Department
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


    /**
     * Add category
     *
     * @param Category $category
     *
     * @return Department
     */
    public function addCategory(Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add store
     *
     * @param Store $store
     *
     * @return Department
     */
    public function addStore(Store $store)
    {
        $this->store[] = $store;

        return $this;
    }

    /**
     * Remove store
     *
     * @param Store $store
     */
    public function removeStore(Store $store)
    {
        $this->store->removeElement($store);
    }

    /**
     * Get store
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStore()
    {
        return $this->store;
    }
}
