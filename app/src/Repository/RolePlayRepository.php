<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\RolePlay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RolePlay|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolePlay|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolePlay[]    findAll()
 * @method RolePlay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolePlayRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, RolePlay::class);
    }

    /**
     * @param Game $game
     * @param int $page
     * @return PaginationInterface
     */
    public function findLatest(Game $game, int $page = 1) : PaginationInterface
    {
        $qb = $this->createQueryBuilder('rp')
            ->addSelect('persona')
            ->innerJoin('rp.persona', 'persona')
            ->where('rp.game = :game')
            ->setParameter('game', $game)
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
            RolePlay::MAX_PER_PAGE
        );

        return $paginator;
    }


}
