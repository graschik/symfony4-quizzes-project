<?php

declare(strict_types=1);

namespace App\Service\Ajax;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class AjaxQuestionsSearch
{
    private $entityManager;

    /**
     * AjaxQuestionsSearch constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $text
     * @return array
     */
    public function searchQuestions(string $text): array
    {
        $questions = $this->entityManager
            ->getRepository(Question::class)
            ->findEntitiesByString($text);

        if (!$questions) {
            $result['entities']['error'] = 'Вопросы не найдены';
        } else {
            $result['entities'] = $this->getFoundData($questions);
        }

        return $result;
    }

    /**
     * @param array& $questions
     * @return array
     */
    public function getFoundData(array& $questions): array
    {
        $foundData = [];

        foreach ($questions as $question) {
            $foundData[$question->getId()] = $question->getText();
        }
        return $foundData;
    }
}