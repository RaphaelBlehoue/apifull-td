<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promotion
 *
 * @ORM\Table(name="promotions")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\PromotionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=6, nullable=true)
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $code;

    /**
     * @var string
     * @Assert\NotNull(message="Le nom de la promotion ne peut être null", groups={"promotion_default"})
     * @Assert\NotBlank(message="Veuillez entrez le nom de votre promotion", groups={"promotion_default"})
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Entrez la description de la promotion ", groups={"promotion_default"})
     * @Assert\NotNull(message="La description est vide", groups={"promotion_default"})
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $content;

    /**
     * @var int
     * @ORM\Column(name="percent", type="integer")
     * @Assert\NotNull(message="Le pourcentage de la promotion ne peut être null", groups={"promotion_default"})
     * @Assert\NotBlank(message="Veuillez entrez le pourcentage de votre promotion", groups={"promotion_default"})
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $percent;

    /**
     * @var bool
     * @ORM\Column(name="actived", type="boolean")
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $actived;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="promotions")
     * @ORM\JoinColumn(referencedColumnName="id", name="product_id", onDelete="CASCADE")
     * @Serializer\Groups({"promotions"})
     * @Serializer\Since("0.1")
     */
    protected $product;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Promotion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content.
     *
     * @param string|null $content
     *
     * @return Promotion
     */
    public function setContent($content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set percent.
     *
     * @param int $percent
     *
     * @return Promotion
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent.
     *
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set actived.
     *
     * @param bool $actived
     *
     * @return Promotion
     */
    public function setActived($actived)
    {
        $this->actived = $actived;

        return $this;
    }

    /**
     * Get actived.
     *
     * @return bool
     */
    public function getActived()
    {
        return $this->actived;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Promotion
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->created = new \DateTime('now');
        $this->code = $this->generateCode(8, 10);
        $this->actived = true;
    }

    /**
     * @param $x
     * @param $y
     * @return string
     */
    private function generateCode($x, $y){
        $length = rand($x,$y);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return strtoupper($randomString);
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return Promotion
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set product.
     *
     * @param Product|null $product
     *
     * @return Promotion
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }
}
