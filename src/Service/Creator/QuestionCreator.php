<?php

declare(strict_types=1);

namespace App\Service\Creator;


use App\Entity\Question;
use App\Service\Util\QuestionUtils;
use Doctrine\ORM\EntityManagerInterface;

class QuestionCreator
{
    private $entityManager;

    private $questionUtils;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuestionUtils $questionUtils
    )
    {
        $this->entityManager = $entityManager;
        $this->questionUtils = $questionUtils;
    }

    public function create(Question $question)
    {
        $this->questionUtils->deleteWhiteSpaces($question);
        $this->questionUtils->addQuestionCharacterIfNotExist($question);

        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }
}