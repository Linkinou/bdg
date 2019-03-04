<?php

namespace App\Repository;

use App\Entity\RolePlay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RolePlay|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolePlay|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolePlay[]    findAll()
 * @method RolePlay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolePlayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RolePlay::class);
    }

    // /**
    //  * @return RolePlay[] Returns an array of RolePlay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RolePlay
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
