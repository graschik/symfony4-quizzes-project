<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuestionUpdateFormValidator
{
    protected $isValid;

    protected $errorMessages;

    private $entityManager;

    /**
     * QuizCreateFormValidator constructor.
     * @param bool $isValid
     * @param array $errorMessages
     */
    public function __construct(
        bool $isValid = true,
        array $errorMessages = [],
        EntityManagerInterface $entityManager
    )
    {
        $this->isValid = $isValid;
        $this->errorMessages = $errorMessages;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Quiz $data
     * @return bool
     */
    public function validate(Question $data): bool
    {
        //TODO should divide this method on small pieces

        $this->setIsValid(true);

        if($data->getText() == '' || $data->getText() == null){
            $this->setIsValid(false);
            $this->addMessage('Текст вопроса не должен быть пустым');
        }

        $answers = $data->getAnswers();

        if(count($answers) < 2){
            $this->setIsValid(false);
            $this->addMessage('Ответов должно быть больше 1');
        }

        $isCorrectAmount = 0;
        foreach($answers as $answer){
            if($answer->getIsCorrect()){
                $isCorrectAmount++;
            }
        }

        if($isCorrectAmount != 1){
            $this->setIsValid(false);
            $this->addMessage('Должен быть 1 правильный ответ');
        }

        foreach($answers as $outerAnswer){
            $outerAnswerText = strtolower($outerAnswer->getText());

            foreach($answers as $innerAnswer){
                if($innerAnswer != $outerAnswer &&
                    strtolower($innerAnswer->getText()) == $outerAnswerText
                ){
                    $this->setIsValid(false);
                    $this->addMessage('Не должно быть одинаковых ответов.');

                    break(2);
                }
            }
        }

        return $this->getIsValid();
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return void
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    /**
     * @return array||null
     */
    public function getErrorMessages(): ?array
    {
        return $this->errorMessages;
    }

    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @param string $errorMessages
     */
    public function setErrorMessages(string $errorMessages): void
    {
        $this->errorMessages = $errorMessages;
    }


}