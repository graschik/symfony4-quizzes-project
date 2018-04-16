<?php
/**
 * Created by Listratenko Stas.
 * Date: 27.03.2018
 * Time: 23:34
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
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
     *     message = "Поле не должно быть пустым"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="quiz", orphanRemoval=true)
     */
    private $games;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", inversedBy="quizzes",cascade={"persist"})
     */
    private $questions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    public function __construct(
        string $name = '',
        ?string $description = '',
        bool $isActive = true
    )
    {
        $this->users = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->questions = new ArrayCollection();

        $this->name = $name;
        $this->description = $description;
        $this->isActive = $isActive;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setQuiz($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getQuiz() === $this) {
                $game->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
