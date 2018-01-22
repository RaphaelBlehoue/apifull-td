<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="status_read", type="boolean", nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $statusRead;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=20, nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $origin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $subject;

    /**
     * @var string
     * 
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(name="actor", type="string", length=50, nullable=true)
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $actor;

    
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notification")
     * @ORM\JoinColumn(referencedColumnName="id", name="user_id", onDelete="CASCADE")
     * @Serializer\Groups({"notification"})
     * @Serializer\Since("0.1")
     */
    protected $user;


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
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set statusRead
     *
     * @param boolean $statusRead
     *
     * @return Notification
     */
    public function setStatusRead($statusRead)
    {
        $this->statusRead = $statusRead;

        return $this;
    }

    /**
     * Get statusRead
     *
     * @return bool
     */
    public function getStatusRead()
    {
        return $this->statusRead;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Notification
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Notification
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
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
     * Set subject
     *
     * @param string $subject
     *
     * @return Notification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set actor
     *
     * @param string $actor
     *
     * @return Notification
     */
    public function setActor($actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return string
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * set Content
     *
     * @param string $content
     *
     * @return Notification
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function entityAction(){
        $this->statusRead = false;
        $this->created = new \DateTime('now');
    }


    /**
     * Set user.
     *
     * @param \Labs\ApiBundle\Entity\User|null $user
     *
     * @return Notification
     */
    public function setUser(\Labs\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Labs\ApiBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
