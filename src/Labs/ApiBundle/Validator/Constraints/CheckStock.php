<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 19/01/2018
 * Time: 19:54
 */

namespace Labs\ApiBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckStock extends Constraint
{
    public $message = 'Le stock disponible est inférieur a la quantité à sortie';
    public $em = null;
    public $service = 'stock.entity.validator';
    public $repositoryMethod = 'getLastStockLineBeforeNewPersist';
    public $entityClass = null;
    public $repository = null;
    public $field;
    public $errorPath;


    public function getRequiredOptions()
    {
        return ['field'];
    }

    public function getDefaultOption()
    {
        return 'field';
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return $this->service;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}