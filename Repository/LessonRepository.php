<?php

namespace StudentBundle\Repository;

use StudentBundle\Entity\Lesson;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends EntityRepository
{
    public function getRatesByLessons(int $studentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(t.lesson) AS lessonId', 'SUM(r.rate) as sum')
            ->from('StudentBundle\Entity\Rating', 'r')
            ->leftJoin('r.task', 't', Expr\Join::WITH, 'IDENTITY(r.task) = t.id')
            ->where('IDENTITY(r.student) = :studentId')
            ->groupBy('lessonId')
            ->orderBy('lessonId', 'asc')
            ->setParameter('studentId', $studentId);

        return $qb->getQuery()->getResult();
    }
}