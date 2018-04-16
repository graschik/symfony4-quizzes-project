<?php
/**
 * Created by Listratenko Stas.
 * Date: 27.03.2018
 * Time: 23:32
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым."
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question", cascade={"persist"})
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz", mappedBy="questions",cascade={"persist"})
     */
    private $quizzes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="currentQuestion")
     */
    private $game;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->addQuestion($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->contains($quiz)) {
            $this->quizzes->removeElement($quiz);
            $quiz->removeQuestion($this);
        }

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
