<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     */
    private $currentQuestion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $correctQuestionsAmount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCorrectCurrentAnswer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfQuestionsAnswered;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gameIsOver;

    public function __construct()
    {
        $this->currentQuestion = new Question();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getCurrentQuestion(): ?Question
    {
        return $this->currentQuestion;
    }

    public function setCurrentQuestion(?Question $currentQuestion): self
    {
        $this->currentQuestion = $currentQuestion;

        return $this;
    }

    public function getCorrectQuestionsAmount(): ?int
    {
        return $this->correctQuestionsAmount;
    }

    public function setCorrectQuestionsAmount(?int $correctQuestionsAmount): self
    {
        $this->correctQuestionsAmount = $correctQuestionsAmount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsCorrectCurrentAnswer()
    {
        return $this->isCorrectCurrentAnswer;
    }

    /**
     * @param mixed $isCorrectCurrentAnswer
     */
    public function setIsCorrectCurrentAnswer($isCorrectCurrentAnswer)
    {
        $this->isCorrectCurrentAnswer = $isCorrectCurrentAnswer;
    }

    /**
     * @return mixed
     */
    public function getNumberOfQuestionsAnswered()
    {
        return $this->numberOfQuestionsAnswered;
    }

    /**
     * @param mixed $numberOfQuestionsAnswered
     */
    public function setNumberOfQuestionsAnswered($numberOfQuestionsAnswered)
    {
        $this->numberOfQuestionsAnswered = $numberOfQuestionsAnswered;
    }

    /**
     * @return mixed
     */
    public function getGameIsOver()
    {
        return $this->gameIsOver;
    }

    /**
     * @param mixed $gameIsOver
     */
    public function setGameIsOver($gameIsOver)
    {
        $this->gameIsOver = $gameIsOver;
    }

}
