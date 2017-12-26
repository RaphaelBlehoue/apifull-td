<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * Brand (marque du product)
 *
 * @ORM\Table("brands")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\BrandRepository")
 * @UniqueEntity(fields={"name"},groups={"brand_default"} ,message="Ce nom de marque existe déjà")
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_brand_api_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"brands","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_brand_api_created",
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"brands","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "update",
 *      href = @Hateoas\Route(
 *          "update_brand_api_updated",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"brands","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_brand_api_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"brands","products"}
 *     )
 * )
 *
 */
class Brand
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"brands","products"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez une marque avant enregistrement", groups={"brand_default"})
     * @Assert\NotNull(message="Le champs est null", groups={"brand_default"})
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Serializer\Groups({"brands","products"})
     * @Serializer\Since("0.1")
    */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"brands","products"})
     * @Serializer\Since("0.1")
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
     * @return integer
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
     * @return Brand
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
     * Add product
     *
     * @param Product $product
     *
     * @return Brand
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
