<?php

namespace App\Repository;

use App\Entity\CustomerDomains;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerDomains|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerDomains|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerDomains[]    findAll()
 * @method CustomerDomains[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerDomainsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerDomains::class);
    }

    // /**
    //  * @return CustomerDomains[] Returns an array of CustomerDomains objects
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
    public function findOneBySomeField($value): ?CustomerDomains
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
