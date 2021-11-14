<?php

namespace StudentBundle\Repository;

use StudentBundle\Entity\Spectrum;
use Doctrine\ORM\EntityRepository;

/**
 * @method Spectrum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spectrum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spectrum[]    findAll()
 * @method Spectrum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpectrumRepository extends EntityRepository
{
}