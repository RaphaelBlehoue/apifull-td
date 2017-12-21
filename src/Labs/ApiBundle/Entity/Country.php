<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;



/**
 * Country
 *
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_country_api_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"country"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_country_api_created",
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"country"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "update",
 *      href = @Hateoas\Route(
 *          "update_country_api_updated",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"country"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_country_api_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"country"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "city",
 *      embedded = @Hateoas\Embedded("expr(object.getCity())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getCity() === null)",
 *          groups={"country"}
 *     )
 * )
 *
 * @ORM\Table(name="countries", options={"comment":"entity reference countries"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CountryRepository")
 *
 */
class Country
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"country","city","country_only"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du pays", groups={"country_default"})
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Serializer\Groups({"country","city","country_only"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=10, nullable=true)
     * @Serializer\Groups({"country","city","country_only"})
     * @Serializer\Since("0.1")
     */
    protected $code;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="City", mappedBy="country", cascade={"remove"})
     * @Serializer\Groups({"country"})
     * @Serializer\Since("0.1")
     */
    protected $city;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->city = new ArrayCollection();
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
     * @return Country
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
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Add city
     *
     * @param City $city
     *
     * @return Country
     */
    public function addCity(City $city)
    {
        $this->city[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param City $city
     */
    public function removeCity(City $city)
    {
        $this->city->removeElement($city);
    }

    /**
     * Get city
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCity()
    {
        return $this->city;
    }

}
