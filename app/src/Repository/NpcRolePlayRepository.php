<?php

namespace App\Repository;

use App\Entity\NpcRolePlay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NpcRolePlay|null find($id, $lockMode = null, $lockVersion = null)
 * @method NpcRolePlay|null findOneBy(array $criteria, array $orderBy = null)
 * @method NpcRolePlay[]    findAll()
 * @method NpcRolePlay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NpcRolePlayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NpcRolePlay::class);
    }

    // /**
    //  * @return NpcRolePlay[] Returns an array of NpcRolePlay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NpcRolePlay
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
