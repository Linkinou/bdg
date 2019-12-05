<?php

namespace App\Repository;

use App\Entity\GameNpc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameNpc|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameNpc|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameNpc[]    findAll()
 * @method GameNpc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameNpcRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameNpc::class);
    }

    // /**
    //  * @return GameNpc[] Returns an array of GameNpc objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameNpc
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
