<?php

namespace App\Repository;

use App\Entity\PossederDroitDash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PossederDroitDash|null find($id, $lockMode = null, $lockVersion = null)
 * @method PossederDroitDash|null findOneBy(array $criteria, array $orderBy = null)
 * @method PossederDroitDash[]    findAll()
 * @method PossederDroitDash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PossederDroitDashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PossederDroitDash::class);
    }

    /**
     * @param $value
     * @return PossederDroitDash[] Returns an array of PossederDroitDash objects
     */

    public function findByUser($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?PossederDroitDash
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
