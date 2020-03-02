<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Game::class);
    }

    /**
     * @param int $page
     * @return PaginationInterface
     */
    public function findLatest(int $location, int $page = 1) : PaginationInterface
    {
        $qb = $this->createQueryBuilder('g')
            ->addSelect('gm')
            ->innerJoin('g.gameMaster', 'gm')
            ->where('g.location = :location')
            ->setParameter('location', $location)
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    /**
     * @param Query $query
     * @param int $page
     * @return PaginationInterface
     */
    private function createPaginator(Query $query, int $page) : PaginationInterface
    {
        $paginator = $this->paginator->paginate(
            $query,
            $page,
            Game::MAX_PER_PAGE
        );

        return $paginator;
    }
}
