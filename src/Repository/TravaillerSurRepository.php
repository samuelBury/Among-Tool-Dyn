<?php

namespace App\Repository;

use App\Entity\TravaillerSur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TravaillerSur|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravaillerSur|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravaillerSur[]    findAll()
 * @method TravaillerSur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravaillerSurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravaillerSur::class);
    }

     /**
      * @return TravaillerSur[] Returns an array of TravaillerSur objects
      */

    public function findByUser($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.User = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return TravaillerSur[] Returns an array of TravaillerSur objects
     */

    public function findByDashboard($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.Dashboard = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?TravaillerSur
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
