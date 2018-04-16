<?php

declare(strict_types=1);

namespace App\Service\Paginator;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class QuestionPaginator
{
    const QUESTIONS_ON_PAGE = 15;

    private $entityManager;

    private $paginator;

    /**
     * QuestionPaginator constructor.
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @return SlidingPagination
     */
    public function createPaginator(Request $request): SlidingPagination
    {
        $dql = "SELECT u FROM App\Entity\Question u";
        $query = $this->entityManager->createQuery($dql);

        $this->paginator = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::QUESTIONS_ON_PAGE
        );

        return $this->paginator;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @param PaginatorInterface $paginator
     * @return void
     */
    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }
}