<?php

namespace App\Repository;

use App\Entity\EventRolePlay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventRolePlay|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRolePlay|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRolePlay[]    findAll()
 * @method EventRolePlay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRolePlayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventRolePlay::class);
    }

    // /**
    //  * @return EventRolePlay[] Returns an array of EventRolePlay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventRolePlay
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
