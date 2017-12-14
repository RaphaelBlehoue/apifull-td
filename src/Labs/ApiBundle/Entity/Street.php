<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labs\ApiBundle\DTO\StreetDTO;
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
 *          parameters = {"city_id" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_street_api_created",
 *          parameters = {"city_id" = "expr(object.getCity().getId())"},
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_street_api_updated",
 *          parameters = {"city_id" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "remove",
 *      href = @Hateoas\Route(
 *          "remove_street_api_delete",
 *          parameters = {"city_id" = "expr(object.getCity().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"street"}
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
     * @Serializer\Groups({"city","street"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du quartier", groups={"street_default"})
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"city","street"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="City", inversedBy="Street")
     * @Serializer\Groups({"street"})
     * @Serializer\Since("0.1")
     */
    protected $city;


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

    public function updateFromDTO(StreetDTO $DTO){
        $this->setName($DTO->getName());
        return $this;
    }
}
