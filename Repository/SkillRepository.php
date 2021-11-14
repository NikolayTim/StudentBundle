<?php

namespace StudentBundle\Repository;

use StudentBundle\Entity\Skill;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends EntityRepository
{
    public function getRatesBySkills(int $studentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(sp.skill) AS skillId', 'SUM(r.rate * sp.percent) AS sum')
            ->from('StudentBundle\Entity\Rating', 'r')
            ->leftJoin('StudentBundle\Entity\Spectrum', 'sp', Expr\Join::WITH, 'IDENTITY(sp.task) = IDENTITY(r.task)')
            ->where('IDENTITY(r.student) = :studentId')
            ->groupBy('skillId')
            ->orderBy('skillId', 'asc')
            ->setParameter('studentId', $studentId);

        return $qb->getQuery()->getResult();
    }
}
