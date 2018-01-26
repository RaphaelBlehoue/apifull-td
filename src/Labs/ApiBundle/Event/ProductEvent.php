<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 10:26
 */

namespace Labs\ApiBundle\Event;


use Labs\ApiBundle\Entity\Product;
use Symfony\Component\EventDispatcher\Event;

class ProductEvent extends Event
{

    /**
     * @var Product
     */
    protected $product;


    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

}