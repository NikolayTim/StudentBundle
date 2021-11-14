<?php

namespace StudentBundle\Repository;

use StudentBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends EntityRepository
{
    public function getRatesByCourses(int $studentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(l.course) AS moduleId', 'SUM(r.rate) AS sum')
            ->from('StudentBundle\Entity\Rating', 'r')
            ->leftJoin('r.task', 't', Expr\Join::WITH, 'IDENTITY(r.task) = t.id')
            ->leftJoin('t.lesson', 'l', Expr\Join::WITH, 'IDENTITY(t.lesson) = l.id')
            ->where('IDENTITY(r.student) = :studentId')
            ->groupBy('moduleId')
            ->orderBy('moduleId', 'asc')
            ->setParameter('studentId', $studentId);

        return $qb->getQuery()->getResult();
    }
}