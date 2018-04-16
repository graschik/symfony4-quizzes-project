<?php

declare(strict_types=1);

namespace App\Service\Creator;


use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizCreator
{
    private $entityManager;

    /**
     * QuizCreator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Quiz $data
     * @return void
     */
    public function create(Quiz $data): void
    {
        $quiz = new Quiz(
            $data->getName(),
            $data->getDescription(),
            true
        );

        foreach ($data->getQuestions() as $question) {
            $quiz->addQuestion($this
                ->entityManager
                ->getRepository(Question::class)
                ->findOneBy(['text' => $question->getText()])
            );
        }

        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }
}