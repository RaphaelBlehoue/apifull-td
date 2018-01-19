<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;



/**
 * Street
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_street_api_show",
 *          parameters = {"cityId" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street","store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_street_api_created",
 *          parameters = {"cityId" = "expr(object.getCity().getId())"},
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street","store_groups"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_street_api_updated",
 *          parameters = {"cityId" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street","store_groups"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "remove",
 *      href = @Hateoas\Route(
 *          "remove_street_api_delete",
 *          parameters = {"cityId" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street","store_groups"}
 *     )
 * )
 *
 * @ORM\Table(name="streets", options={"comment":"entity reference street"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\StreetRepository")
 */
class Street
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"city","street","store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du quartier", groups={"street_default"})
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"city","street","store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="City", inversedBy="street")
     * @Serializer\Groups({"street","store_groups"})
     * @Serializer\Since("0.1")
     */
    protected $city;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Store", mappedBy="street")
     * @Serializer\Groups({"street"})
     * @Serializer\Since("0.1")
     */
    protected $store;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->store = new ArrayCollection();
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
     * @return Street
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
     * Set city
     *
     * @param  $city
     *
     * @return Street
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return 
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * Add store
     *
     * @param Store $store
     *
     * @return Street
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
