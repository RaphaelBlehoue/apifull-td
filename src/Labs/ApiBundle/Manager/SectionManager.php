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
use Labs\ApiBundle\Entity\Section;
use Labs\ApiBundle\DTO\SectionDTO;
use Labs\ApiBundle\Repository\SectionRepository;


/**
 * Class SectionManager
 * @package Labs\ApiBundle\Manager
 * @DI\Service("api.section_manager", public=true)
 *
 */
class SectionManager extends ApiEntityManager
{
    /**
     * @var SectionRepository
     */
    protected $repo;

    /**
     * SectionManager constructor.
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
        $this->repo = $this->em->getRepository('LabsApiBundle:Section');
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
        $this->qb->orderBy('s.'.$column, $direction);
        return $this;
    }


    public function create(Category $category, Section $section)
    {
        $section->setCategory($category);
        $this->em->persist($section);
        $this->em->flush();
        return $section;
    }


    public function update(Section $section, SectionDTO $dto)
    {
        $section->setName($dto->getName())
            ->setOnline($dto->getOnline());
        return $section;
    }


    public function patch(Section $section, $fieldName, $fieldValue)
    {
        if ($fieldName == 'online') {
            $section->setOnline($fieldValue);
        }
        $this->em->merge($section);
        $this->em->flush();
        return $section;
    }

    /**
     * @param $category
     * @param $id
     * @return bool
     */
    public function findSectionByCategory($category, $id)
    {
        $data = $this->repo->getSectionByCategory($category, $id)->getQuery()->getOneOrNullResult();
        if ($data === null){
            return false;
        }
        return true;
    }
}