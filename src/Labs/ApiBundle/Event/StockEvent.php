<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 10:26
 */

namespace Labs\ApiBundle\Event;


use Labs\ApiBundle\Entity\Stock;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class StockEvent extends Event
{

    /**
     * @var Stock
     */
    protected $stock;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Stock $stock, Request $request)
    {
        $this->stock = $stock;
        $this->request = $request;
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

}