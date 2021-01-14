<?php

namespace App\Repository;

use App\Entity\CustomerDomain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerDomain[]    findAll()
 * @method CustomerDomain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerDomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerDomain::class);
    }

    public function findAllCustomerWithoutPakiet()
    {
        $user = $this->getDoctrine()
            ->getRepository(CustomerDomain::class)
            ->findAll();

        return $user;
    }

    // /**
    //  * @return CustomerDomain[] Returns an array of CustomerDomain objects
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
    public function findOneBySomeField($value): ?CustomerDomain
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
