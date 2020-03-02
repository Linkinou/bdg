<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Topic::class);
    }

    /**
     * @param int $page
     * @return PaginationInterface
     */
    public function findLatest(int $category, int $page = 1) : PaginationInterface
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('a')
            ->innerJoin('p.author', 'a')
            ->where('p.category = :category')
            ->orderBy('p.lastPostCreatedAt', 'DESC')
            ->setParameter('category', $category)
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
            Topic::MAX_PER_PAGE
        );

        return $paginator;
    }
}
