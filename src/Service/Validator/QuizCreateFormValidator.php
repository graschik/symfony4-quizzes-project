<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizCreateFormValidator
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
     * @param Quiz $data
     * @return bool
     */
    public function validate(Quiz $data): bool
    {
        $this->setIsValid(true);

        if(count($data->getQuestions()) < 2){
            $this->setIsValid(false);
            $this->addMessage('Должны быть минимум 2 вопроса');
        }

        foreach($data->getQuestions() as $outerQuestion){
            $outerQuestionText = strtolower($outerQuestion->getText());

            foreach($data->getQuestions() as $innerQuestion){
                if($innerQuestion != $outerQuestion &&
                    strtolower($innerQuestion->getText()) == $outerQuestionText
                ){
                    $this->setIsValid(false);
                    $this->addMessage('Не должно быть одинаковых вопросов.');

                    break(2);
                }
            }
        }

        $count = 1;
        foreach ($data->getQuestions() as $question){
            if(!$this
                ->entityManager
                ->getRepository(Question::class)
                ->findOneBy(['text' => $question->getText()])
            ){
                $this->setIsValid(false);
                $this->addMessage('Вопроса под номером ' . $count . ' не существует');
            }

            $count++;
        }

        return $this->isValid;
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