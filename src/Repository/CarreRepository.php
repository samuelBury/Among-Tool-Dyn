<?php

namespace App\Repository;

use App\Entity\Carre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carre[]    findAll()
 * @method Carre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carre::class);
    }

    // /**
    //  * @return Carre[] Returns an array of Carre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Carre
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
