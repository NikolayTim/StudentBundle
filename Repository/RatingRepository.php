<?php

namespace StudentBundle\Repository;

use StudentBundle\Entity\Rating;
use Doctrine\ORM\EntityRepository;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends EntityRepository
{
    public function getRatesByDateTime(int $studentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('r.createdAt', 'SUM(r.rate) as sum')
            ->from('StudentBundle\Entity\Rating', 'r')
            ->where('IDENTITY(r.student) = :studentId')
            ->groupBy('r.createdAt')
            ->orderBy('r.createdAt', 'asc')
            ->setParameter('studentId', $studentId);

        return $qb->getQuery()->getResult();
    }

    public function findRating(int $taskId, int $studentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('r')
            ->from('StudentBundle\Entity\Rating', 'r')
            ->where('IDENTITY(r.student) = :studentId AND IDENTITY(r.task) = :taskId')
            ->setParameter('studentId', $studentId)
            ->setParameter('taskId', $taskId);

        return $qb->getQuery()->getResult();
    }
}