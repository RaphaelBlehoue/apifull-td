<?php

namespace Labs\ApiBundle\DBAL\Types\Stock;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 18/01/2018
 * Time: 12:42
 */

final class StockOriginType extends AbstractEnumType
{
     const STOCK_INITIAL = 'SI';
     const VENTES = 'VT';
     const ACHATS = 'AC';
     const MANUAL = 'MN';

    protected static $choices = [
        self::ACHATS => 'achats',
        self::VENTES => 'ventes',
        self::STOCK_INITIAL => 'stock Inital',
        self::MANUAL => 'manuel'
    ];
}