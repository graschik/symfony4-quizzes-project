<?php

namespace App\Service\Validator;


use App\Entity\Game;
use App\Entity\Question;
use App\Entity\UserAnswer;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;

class UserAnswerValidator
{
    protected $isValid;

    protected $errorMessages;

    private $entityManager;

    /**
     * QuizCreateFormValidator constructor.
     * @param bool $isValid
     * @param array $errorMessages
     * @param EntityManagerInterface $entityManager
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
     * @param Game|UserAnswer|GameType $data
     * @param Question $question
     * @return bool
     * @internal param Game $game
     */
    public function validate(UserAnswer $data, Question $question): bool
    {
        //TODO should divide this method on small pieces
        if ($data->getNumber() > count($question->getAnswers())) {

            $this->setIsValid(false);
            $this->addMessage("Ответ не может быть больше количества вопросов");
        } else {
            $this->setIsValid(true);
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