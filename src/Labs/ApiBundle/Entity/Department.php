<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Labs\ApiBundle\DTO\DepartmentDTO;


/**
 * Department
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_department_api_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_department_api_created",
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "update",
 *      href = @Hateoas\Route(
 *          "update_department_api_updated",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_department_api_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated_top",
 *      href = @Hateoas\Route(
 *          "patch_department_top_api_patch_top",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated_online",
 *      href = @Hateoas\Route(
 *          "patch_department_online_api_patch_online",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"department"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "category",
 *      embedded = @Hateoas\Embedded("expr(object.getCategory())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getCategory() === null)",
 *          groups={"department"}
 *     )
 * )
 *
 * @ORM\Table(name="departments", options={"comment":"entity reference articles departments"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\DepartmentRepository")
 * @UniqueEntity(fields={"name"},groups={"department_default"} ,message="Ce nom de departement est déja utilisé")
 * @UniqueEntity(fields={"position"},groups={"department_default"} ,message="Cette position est déjà occupé par un autre departement")
 *
 */
class Department
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez un departement", groups={"department_default"})
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var int
     * @Assert\NotNull(message="Entrez la position d'affichage du département", groups={"department_default"})
     * @ORM\Column(name="position", type="integer", unique=true, nullable=false)
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="top", type="boolean")
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $top;

    /**
     * @var bool
     *
     * @ORM\Column(name="online", type="boolean")
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $online;

    /**
     * @Gedmo\Slug(fields={"name","id"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $slug;


    /**
     * @var string
     * @Assert\NotNull(message="Entrez le code couleur hexadecimal du departement, exemple(#FFEBBC)", groups={"department_default"})
     * @ORM\Column(name="color_code", type="string", length=255, nullable=true)
     * @Serializer\Groups({"department","category"})
     * @Serializer\Since("0.1")
     */
    protected $colorCode;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Category", mappedBy="department", cascade={"remove"})
     * @Serializer\Since("0.1")
     */
    protected $category;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Store", mappedBy="department")
     * @Serializer\Groups({"store"})
     * @Serializer\Since("0.1")
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

    public function updateFromDTO(DepartmentDTO $dto){
        $this->setName($dto->getName())
            ->setColorCode($dto->getColorCode())
            ->setTop($dto->getTop())
            ->setOnline($dto->getOnline());

        return $this;
    }
}
