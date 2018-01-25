<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;



/**
 * Command
 *
 * @ORM\Table(name="commands")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CommandRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Command
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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=12, nullable=true)
     */
    protected $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=true)
     */
    protected $origin;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="command")
     */
    protected $orderproduct;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="command")
     * @ORM\JoinColumn(referencedColumnName="id", name="user_id", onDelete="CASCADE")
     */
    protected $user;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderproduct = new ArrayCollection();
    }


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
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Command
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
     * Set status.
     *
     * @param int $status
     *
     * @return Command
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Command
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set origin.
     *
     * @param string|null $origin
     *
     * @return Command
     */
    public function setOrigin($origin = null)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin.
     *
     * @return string|null
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(){
        $this->status = 0;
        $this->created = new \DateTime('now');
        $this->code = $this->generateSku(12, 16);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PostPersist()
     */
    public function preAndPostPersist(){
        $this->updated = new \DateTime('now');
    }

    /**
     * @param $x
     * @param $y
     * @return string
     */
    private function generateSku($x, $y){
        $length = rand($x,$y);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return strtoupper($randomString);
    }


    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Command
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add orderproduct.
     *
     * @param OrderProduct $orderproduct
     *
     * @return Command
     */
    public function addOrderproduct(OrderProduct $orderproduct)
    {
        $this->orderproduct[] = $orderproduct;

        return $this;
    }

    /**
     * Remove orderproduct.
     *
     * @param OrderProduct $orderproduct
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOrderproduct(OrderProduct $orderproduct)
    {
        return $this->orderproduct->removeElement($orderproduct);
    }

    /**
     * Get orderproduct.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderproduct()
    {
        return $this->orderproduct;
    }

    /**
     * Set user.
     *
     * @param User|null $user
     *
     * @return Command
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
