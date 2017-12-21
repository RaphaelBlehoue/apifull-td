<?php

namespace Labs\ApiBundle\DTO;

use JMS\Serializer\Annotation as JMS;
use libphonenumber\PhoneNumber;
use Symfony\Component\Validator\Constraints AS Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;


/**
 * Class StoreDTO
 * @package Labs\ApiBundle\DTO
 */
class StoreDTO
{

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le champs nom de la boutique est vide")
     * @Assert\NotNull(message="Entrez le nom de votre boutique")
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotBlank(message="Le champs description de la boutique est vide")
     * @Assert\NotNull(message="Entrez une description de votre boutique")
     * @JMS\Type("string")
     */
    protected $content;


    /**
     * @var PhoneNumber
     * @JMS\Type("libphonenumber\PhoneNumber")
     * @Assert\NotBlank(message="Le champs numero de téléphone de votre boutique est vide")
     * @Assert\NotNull(message="Numero de téléphone de votre boutique")
     * @AssertPhoneNumber(type="mobile", message="Numero de téléphone incorrect")
     */
    protected $phone;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return StoreDTO
     *
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
     * Set content
     *
     * @param string $content
     *
     * @return StoreDTO
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set phone
     *
     * @param PhoneNumber $phone
     *
     * @return StoreDTO
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return PhoneNumber
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
