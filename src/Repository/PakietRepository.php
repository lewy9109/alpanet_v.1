<?php

namespace App\Repository;

use App\Entity\Pakiet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pakiet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pakiet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pakiet[]    findAll()
 * @method Pakiet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PakietRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pakiet::class);
    }

    // /**
    //  * @return Pakiet[] Returns an array of Pakiet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pakiet
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
