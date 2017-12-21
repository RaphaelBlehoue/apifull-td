<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Category
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_category_api_show",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_category_api_created",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())"},
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_category_api_updated",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_category_api_delete",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "patch_top",
 *      href = @Hateoas\Route(
 *          "patch_category_top_api_patch_top",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "patch_online",
 *      href = @Hateoas\Route(
 *          "patch_category_online_api_patch_online",
 *          parameters = {"departmentId" = "expr(object.getDepartment().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"category"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "section",
 *      embedded = @Hateoas\Embedded("expr(object.getSection())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getSection() === null)",
 *          groups={"category"}
 *     )
 * )
 *
 *
 * @ORM\Table(name="categories", options={"comment":"entity reference sub-departments"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"category","department","section"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez une categorie", groups={"category_default"})
     * @Assert\NotBlank(message="La valeur du champs est vide", groups={"category_default"})
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"category","department","section"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var bool
     * @Assert\Type(type="bool", message="Le type de ce champs est invalide")
     * @Serializer\Groups({"category","department","section"})
     * @Serializer\Since("0.1")
     * @ORM\Column(name="top", type="boolean")
     */
    protected $top;


    /**
     * @Gedmo\Slug(fields={"name"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"category","department","section"})
     * @Serializer\Since("0.1")
     */
    protected $slug;

    /**
     * @var bool
     * @Serializer\Groups({"category","department","section"})
     * @Serializer\Since("0.1")
     * @ORM\Column(name="online", type="boolean")
     */
    protected $online;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="category")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Groups({"category"})
     * @Serializer\Since("0.1")
     */
    protected $department;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Section", mappedBy="category", cascade={"remove"})
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"category"})
     */
    protected $section;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->section = new ArrayCollection();
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
     * @return Category
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
     * Set top
     *
     * @param boolean $top
     *
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
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
     * Set online
     *
     * @param boolean $online
     *
     * @return Category
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
     * Set department
     *
     * @param Department $department
     *
     * @return Category
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
     * Add section
     *
     * @param Section $section
     *
     * @return Category
     */
    public function addSection(Section $section)
    {
        $this->section[] = $section;

        return $this;
    }

    /**
     * Remove section
     *
     * @param Section $section
     */
    public function removeSection(Section $section)
    {
        $this->section->removeElement($section);
    }

    /**
     * Get section
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setPropriety()
    {
        $this->top = false;
        $this->online = true;
    }

}
