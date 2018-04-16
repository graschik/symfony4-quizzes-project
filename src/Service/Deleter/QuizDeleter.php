<?php

declare(strict_types=1);

namespace App\Service\Deleter;


use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizDeleter
{
    private $entityManager;

    /**
     * QuizDeleter constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($id);

        if(!$quiz){
            return false;
        }

        $this->entityManager->remove($quiz);
        $this->entityManager->flush();

        return true;
    }
}