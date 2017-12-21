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
use Labs\ApiBundle\Entity\Category;
use Labs\ApiBundle\DTO\CategoryDTO;
use Labs\ApiBundle\Entity\Department;
use Labs\ApiBundle\Repository\CategoryRepository;


/**
 * Class CategoryManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.category_manager", public=true)
 *
 */
class CategoryManager extends ApiEntityManager
{
    /**
     * @var CategoryRepository
     */
    protected $repo;

    /**
     * CategoryManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Category');
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
        $this->qb->orderBy('c.'.$column, $direction);
        return $this;
    }

    /**
     * @param Department $department
     * @param Category $category
     * @return Category
     */
    public function create(Department $department, Category $category)
    {
        $category->setDepartment($department);
        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    /**
     * @param Category $category
     * @param CategoryDTO $dto
     * @return Category
     */
    public function update(Category $category, CategoryDTO $dto)
    {
        $category->setName($dto->getName())
            ->setTop($dto->getTop())
            ->setOnline($dto->getOnline());
        return $category;
    }

    /**
     * @param Category $category
     * @param $fieldName
     * @param $fieldValue
     * @return Category
     */
    public function patch(Category $category, $fieldName, $fieldValue)
    {
        if ($fieldName == 'top') {
            $category->setTop($fieldValue);
        }else{
            $category->setOnline($fieldValue);
        }
        $this->em->merge($category);
        $this->em->flush();
        return $category;
    }

    /**
     * @param $department
     * @param $id
     * @return bool
     */
    public function findCategoryByDepartement($department, $id)
    {
        $data = $this->repo->getCategoryByDepartmentId($department, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}