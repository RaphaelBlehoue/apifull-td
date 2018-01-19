<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 19/01/2018
 * Time: 00:29
 */

namespace Labs\ApiBundle\EventSubscriber\Doctrine;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Labs\ApiBundle\Entity\Stock;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class StockLineSubscriber implements EventSubscriber
{


    /**
     * @var ConstraintViolationListInterface
     */
    protected $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
         return [
             'prePersist'
         ];
    }

    /**
     * @param LifecycleEventArgs $args
     * if type = true => IN || if type = false => OUT
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Stock){
            $stock = $this->getFinalStock($entity->getProduct()->getId(), $args);
            if ($entity->getType() === false) {

            }
            $entity->setStockFn($stock);
        }
    }

    /**
     * @param $productId
     * @param LifecycleEventArgs $args
     * @return float|int
     * Calcul et affectation du stock actuel
     */
    protected function getFinalStock($productId, LifecycleEventArgs $args)
    {
        $QuantityMouvment = $this->getQuantityMouvement($args);
        $stock_initial = $this->getInitialStock($productId, $args);
        return $stock = $stock_initial + ($QuantityMouvment);
    }

    /**
     * @param $productId
     * @param LifecycleEventArgs $args
     * Recuperation du stock final précedent qui sera le stock initial actuel
     * @return int
     */
    protected function getInitialStock($productId, LifecycleEventArgs $args)
    {
        $repository = $args->getEntityManager()->getRepository('LabsApiBundle:Stock');
        $lastLine = $repository->getLastStockLineBeforeNewPersist($productId);
        return $stock_initial = ($lastLine === null) ? 0 : $lastLine->getStockFn();
    }

    /**
     * @param LifecycleEventArgs $args
     * @return float|int
     * Mouvement Type pour déterminer le signe du calcul
     */
    protected function getQuantityMouvement(LifecycleEventArgs $args){
        return $QuantityMouvment = ($args->getEntity()->getType() === true) ? $args->getEntity()->getQuantity() : -1 * abs($args->getEntity()->getQuantity());
    }
}