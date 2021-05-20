<?php

namespace App\Repository;

use App\Entity\DroitDash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DroitDash|null find($id, $lockMode = null, $lockVersion = null)
 * @method DroitDash|null findOneBy(array $criteria, array $orderBy = null)
 * @method DroitDash[]    findAll()
 * @method DroitDash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DroitDashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DroitDash::class);
    }

    // /**
    //  * @return DroitDash[] Returns an array of DroitDash objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DroitDash
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
