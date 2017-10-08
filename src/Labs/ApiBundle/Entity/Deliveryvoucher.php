<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deliveryvoucher (bon de livraison)
 *
 * @ORM\Table("deliveryvouchers")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\DeliveryvoucherRepository")
 */
class Deliveryvoucher
{

    /**
     * @var integer
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
     * @ORM\Column(name="delivery", type="date")
     */
    protected $delivery;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @ORM\OneToOne(targetEntity="Quotation", inversedBy="deliveryvoucher", cascade={"persist"})
     */
    protected $quotation;

    /**
     * @var string
     * @ORM\Column(name="reference", type="string", length=225)
     */
    protected $reference;


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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Deliveryvoucher
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
     * Set delivery
     *
     * @param \DateTime $delivery
     *
     * @return Deliveryvoucher
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return \DateTime
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Deliveryvoucher
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Deliveryvoucher
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set quotation
     *
     * @param Quotation $quotation
     *
     * @return Deliveryvoucher
     */
    public function setQuotation(Quotation $quotation = null)
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * Get quotation
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        return $this->quotation;
    }
}
