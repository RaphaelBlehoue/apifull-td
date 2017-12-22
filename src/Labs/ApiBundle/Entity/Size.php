<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Size
 *
 * @ORM\Table(name="sizes")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\SizeRepository")
 * @UniqueEntity(fields={"size"}, message="Cette taille est déjà enregistré")
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_size_api_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"sizes"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_size_api_created",
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"sizes"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "update",
 *      href = @Hateoas\Route(
 *          "updated_size_api_updated",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"sizes"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_size_api_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"sizes"}
 *     )
 * )
 */
class Size
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"sizes","products"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez la valeur de la taille", groups={"size_default"})
     * @Assert\NotNull(message="Le champs est vide", groups={"size_default"})
     * @ORM\Column(name="size", type="string", length=6, unique=true)
     * @Serializer\Groups({"sizes","products"})
     * @Serializer\Since("0.1")
     */
    protected $size;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="size")
     */
    protected $products;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Set size
     *
     * @param string $size
     *
     * @return Size
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * Add product
     *
     * @param Product $product
     *
     * @return Size
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
