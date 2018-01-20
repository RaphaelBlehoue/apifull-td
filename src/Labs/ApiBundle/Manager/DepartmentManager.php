<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/12/2017
 * Time: 20:32
 */

namespace Labs\ApiBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\DTO\DepartmentDTO;
use Labs\ApiBundle\Repository\DepartmentRepository;


/**
 * Class DepartmentManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.department_manager", public=true)
 *
 */


class DepartmentManager extends ApiEntityManager
{
    /**
     * @var DepartmentRepository
     */
    protected $repo;

    /**
     * DepartmentManager constructor.
     * @param EntityManagerInterface $em
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }


    protected function setRepository()
    {
        $this->repo = $this->em->getRepository('LabsApiBundle:Department');
        return $this;
    }

    public function getList()
    {
        $this->qb = $this->repo->getListQB();
        return $this;
    }

    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function order($column, $direction)
    {
        $this->qb->orderBy('d.'.$column, $direction);
        return $this;
    }

    /**
     * @inheritdoc({creation du Department})
     * @param Department $department
     * @return Department
     */
    public function create(Department $department)
    {
        $this->em->persist($department);
        $this->em->flush();
        return $department;
    }

    /**
     * @param Department $department
     * @param DepartmentDTO $dto
     * @return Department
     */
    public function update(Department $department, DepartmentDTO $dto)
    {
        $department->setName($dto->getName())
            ->setColorCode($dto->getColorCode())
            ->setPosition($dto->getPosition())
            ->setOnline($dto->getOnline())
            ->setTop($dto->getTop());
        return $department;
    }

    public function patch(Department $department, $fieldName, $fieldValue)
    {
        if ($fieldName == 'top') {
            $department->setTop($fieldValue);
        }else{
            $department->setOnline($fieldValue);
        }
        $this->em->merge($department);
        $this->em->flush();
        return $department;
    }

    public function where($options)
    {
        // TODO: Implement where() method.
    }
}