<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 19/01/2018
 * Time: 19:38
 */

namespace Labs\ApiBundle\Validator\Constraints;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Product;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


/**
 * Class CheckStockValidator
 * @package Labs\ApiBundle\Validator\Constraints
 */

class CheckStockValidator extends ConstraintValidator
{

    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * CheckStockValidator constructor.
     * @param ManagerRegistry $registry
     * @DI\InjectParams({
     *     "registry" = @DI\Inject("doctrine")
     * })
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CheckStock) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\CheckStock');
        }

    }
}