<?php

namespace App\Repository;

use App\Entity\GameEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameEvent[]    findAll()
 * @method GameEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameEvent::class);
    }

    // /**
    //  * @return GameEvent[] Returns an array of GameEvent objects
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
    public function findOneBySomeField($value): ?GameEvent
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
