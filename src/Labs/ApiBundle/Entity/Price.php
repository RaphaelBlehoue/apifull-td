<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Price
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_price_api_show",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_price_api_created",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_price_api_updated",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "patch_price_negociation",
 *      href = @Hateoas\Route(
 *          "patch_price_negociation_status_api_patch_negociate",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "patch_price_actived",
 *      href = @Hateoas\Route(
 *          "patch_price_actived_status_api_patch_actived",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "remove",
 *      href = @Hateoas\Route(
 *          "remove_price_api_delete",
 *          parameters = {
 *              "productId" = "expr(object.getProduct().getId())",
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"prices","products"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "products",
 *      embedded = @Hateoas\Embedded("expr(object.getProduct())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getProduct() === null)",
 *          groups={"prices","products"}
 *     )
 * )
 *
 * @ORM\Table(name="prices")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\PriceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Price
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix d'achat", groups={"price_default"})
     * @ORM\Column(name="buy_price", type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $buyPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez un prix de vente", groups={"price_default"})
     * @ORM\Column(name="sell_price", type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $sellPrice = 0;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez le Seuil du prix de négociation", groups={"price_default"})
     * @ORM\Column(name="negocite_limit_price", type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $negociteLimitPrice = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="negociate", type="boolean", nullable=true)
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $negociate;

    /**
     * @var
     *
     * @ORM\Column(name="actived", type="boolean")
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $actived;

    /**
     * @var
     *
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="price")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"prices"})
     * @Serializer\Since("0.1")
     */
    protected $product;


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
     * Set buyPrice
     *
     * @param string $buyPrice
     *
     * @return Price
     */
    public function setBuyPrice($buyPrice)
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    /**
     * Get buyPrice
     *
     * @return string
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set sellPrice
     *
     * @param string $sellPrice
     *
     * @return Price
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return string
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set negociteLimitPrice
     *
     * @param string $negociteLimitPrice
     *
     * @return Price
     */
    public function setNegociteLimitPrice($negociteLimitPrice)
    {
        $this->negociteLimitPrice = $negociteLimitPrice;

        return $this;
    }

    /**
     * Get negociteLimitPrice
     *
     * @return string
     */
    public function getNegociteLimitPrice()
    {
        return $this->negociteLimitPrice;
    }

    /**
     * Set negociate
     *
     * @param boolean $negociate
     *
     * @return Price
     */
    public function setNegociate($negociate)
    {
        $this->negociate = $negociate;

        return $this;
    }

    /**
     * Get negociate
     *
     * @return bool
     */
    public function getNegociate()
    {
        return $this->negociate;
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
     * set created
     *
     * @param  \DateTime $created
     *
     * @return Price
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function saveDate()
    {
       $this->created = new \DateTime('now');
       $this->negociate = false;
       $this->actived = true;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return Price
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * @return mixed
     */
    public function getActived()
    {
        return $this->actived;
    }


    /**
     * Set Actived
     *
     * @param boolean $actived
     * @return Price
     */
    public function setActived($actived)
    {
        $this->actived = $actived;

        return $this;
    }


}
