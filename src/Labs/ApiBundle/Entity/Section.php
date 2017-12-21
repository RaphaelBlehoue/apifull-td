<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * Section
 *
 * @ORM\Table(name="sections", options={"comment":"entity reference articles section"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\SectionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_section_api_show",
 *          parameters = {"categoryId" = "expr(object.getCategory().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"section"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_section_api_created",
 *          parameters = {"categoryId" = "expr(object.getCategory().getId())"},
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"section"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_section_api_updated",
 *          parameters = {"categoryId" = "expr(object.getCategory().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"section"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "patch_online",
 *      href = @Hateoas\Route(
 *          "patch_section_online_api_patch_online",
 *          parameters = {"categoryId" = "expr(object.getCategory().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"section"}
 *     )
 * )
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"category","section"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="ce champ ne peut etre vide", groups={"section_default"})
     * @Assert\NotNull(message="Entrez une section", groups={"section_default"})
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Serializer\Groups({"category","section"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name","id"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"category","section"})
     * @Serializer\Since("0.1")
     */
    protected $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="online", type="boolean", nullable=true)
     * @Serializer\Groups({"category","section"})
     * @Serializer\Since("0.1")
     */
    protected $online;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="section")
     * @Serializer\Groups({"section"})
     * @Serializer\Since("0.1")
     */
    protected $category;



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
     * @return Section
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
     * @return Section
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
     * @return Section
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
     * Set category
     *
     * @param Category $category
     *
     * @return Section
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setPropriety()
    {
        $this->online = true;
    }
}
