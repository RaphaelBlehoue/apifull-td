<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 18/01/2018
 * Time: 12:36
 */

namespace Labs\ApiBundle\DBAL\Types\Stock;



use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class StockTypeType extends AbstractEnumType
{
     const IN = 'IN';
     const OUT = 'OUT';

    protected static $choices = [
       self::IN =>  'Entrée de stock',
       self::OUT => 'Sortie de stock'
    ];
}