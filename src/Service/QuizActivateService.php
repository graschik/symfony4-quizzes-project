<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizActivateService
{
    private $entityManager;

    /**
     * QuizActivateService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return void
     */
    public function activate(int $id): void
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($id);

        $quiz->setIsActive(true);

        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @return void
     */
    public function disactivate(int $id): void
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($id);

        $quiz->setIsActive(false);

        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }
}