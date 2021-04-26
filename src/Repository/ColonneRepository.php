<?php

namespace App\Repository;

use App\Entity\Colonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Colonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colonne[]    findAll()
 * @method Colonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Colonne::class);
    }

    /**
     * @param $idDash
     * @return Colonne[] Returns an array of Colonne objects
     */

    public function findByIdDashboard($idDash): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dashboard = :val')
            ->setParameter('val', $idDash)
            ->orderBy('c.rank', 'ASC')

            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneByName($value): ?Colonne
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
