<?php

declare(strict_types=1);

namespace App\Service\Util;

use App\Entity\Question;
use App\Service\Deleter\AnswerDeleter;
use Doctrine\ORM\EntityManagerInterface;

class QuestionUtils
{

    private $entityManager;

    private $answerDeleter;

    /**
     * QuestionUtils constructor.
     * @param EntityManagerInterface $entityManager
     * @param AnswerDeleter $answerDeleter
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AnswerDeleter $answerDeleter
    )
    {
        $this->entityManager = $entityManager;
        $this->answerDeleter = $answerDeleter;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function deleteWhiteSpaces(Question $question): Question
    {
        $questionText = $question->getText();
        trim($questionText);
        $question->setText($questionText);

        return $question;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function addQuestionCharacterIfNotExist(Question $question): Question
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) != '?'){
            $questionText .= '?';
            $question->setText($questionText);
        }

        return $question;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function deleteQuestionCharacterIfExist(Question $question): Question
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) == '?'){
            $questionText = substr($questionText, 0, strlen($questionText)-1);
            $question->setText($questionText);
        }

        return $question;
    }

    /**
     * @param Question $question
     * @return void
     */
    public function deleteEmptyAnswers(Question $question): void
    {
        foreach($question->getAnswers() as $answer){
            if($answer->getText() == null || $answer->getText() == ""){
                $question->getAnswers()->removeElement($answer);
                $this->answerDeleter->delete($answer);
            }
        }

    }
}