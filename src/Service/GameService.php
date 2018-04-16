<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 12:10
 */

namespace App\Service;


use App\Entity\Game;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use App\Entity\UserAnswer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GameService
{
    private $entityManager;

    private $userInterface;

    private $timerService;

    private $game;

    private $isCorrect;

    /**
     * GameService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TimerService $timerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TimerService $timerService
    )
    {
        $this->entityManager = $entityManager;
        $this->timerService = $timerService;

        $this->game = new Game();
    }

    /**
     * @param Game $game
     * @param UserInterface $user
     * @return bool
     */
    public function checkPermission(Game $game, UserInterface $user): bool
    {
        $this->game = $game;

        $targetUser = $this
            ->entityManager
            ->getRepository(User::class)
            ->find($game->getUser());

        if ($user->getUsername() != $targetUser->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $quizId
     * @param int $userId
     * @return int
     */
    public function createGame(int $quizId, int $userId): int
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($quizId);

        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->find($userId);

        $this->game
            ->setCurrentQuestion($quiz->getQuestions()[0])
            ->setQuiz($quiz)
            ->setUser($user)
            ->setStatus('in_proccess')
            ->setCorrectQuestionsAmount(0)
            ->setTime(0);

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();

        return $this->game->getId();
    }

    /**
     * @param int $gameId
     * @return Game|null
     */
    public function play(int $gameId): ?Game
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->find($gameId);

        $this->timerService->startTimer();

        $this->game = $game;

        return $this->game;
    }

    public function stop()
    {
        $this->timerService->stopTimer();
        $this->game->setTime($this->timerService->getTimerDuration());
        $this->game->setNumberOfQuestionsAnswered($this->game->getNumberOfQuestionsAnswered() + 1);

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();
    }

    /**
     * @param UserAnswer $result
     * @param Question $question
     * @return bool
     */
    public function checkResult(UserAnswer $result, Question $question): bool
    {
        if ($question->getAnswers()[$result->getNumber() - 1]->getIsCorrect() != 1) {
            $this->game->setIsCorrectCurrentAnswer(false);

        } else {
            $this->game->setIsCorrectCurrentAnswer(true);
            $this
                ->game
                ->setCorrectQuestionsAmount($this->game->getCorrectQuestionsAmount() + 1);
        }

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param Game $game
     */
    public function setNextQuestion(Game $game)
    {
        $numberOfCurrentQuestion = $this->getCurrentQuestionNumber($game);
        $quiz = $game->getQuiz();

        $game->setCurrentQuestion($quiz->getQuestions()[$numberOfCurrentQuestion + 1]);

        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }

    /**
     * @param Game $game
     * @return int|null
     */
    public function getCurrentQuestionNumber(Game $game): ?int
    {
        $quiz = $game->getQuiz();
        $questions = $quiz->getQuestions();

        for ($i = 0; $i < count($questions); $i++) {
            if ($questions[$i]->getId() == $game->getCurrentQuestion()->getId()) {
                return $i;
            }
        }

        return null;
    }

    /**
     * @param Game $game
     * @return Question|null
     */
    public function getCurrentQuestion(Game $game)
    {
        return $game->getCurrentQuestion();
    }

    /**
     * @param int $gameId
     * @return Game|null
     */
    public function find(int $gameId): ?Game
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->find($gameId);
        $this->game = $game;

        return $game;
    }

    /**
     * @param int $quizId
     * @return array|null
     */
    public function findAllByQuizId(int $quizId): ?array
    {
        $games = $this
            ->entityManager
            ->getRepository(Game::class)
            ->findBy(['quiz' => $quizId]);

        return $games;
    }

    /**
     * @param int $userId
     * @param int $quizId
     * @return Game|null
     */
    public function findByQuizUserId(int $userId, int $quizId): ?Game
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->findOneBy([
                'user' => $userId,
                'quiz' => $quizId,
            ]);

        return $game;
    }

    /**
     * @param int $gameId
     * @return bool
     */
    public function gameIsOver(int $gameId)
    {
        if ($this
                ->find($gameId)
                ->getNumberOfQuestionsAnswered() == count($this
                ->find($gameId)
                ->getQuiz()->getQuestions())) {

            $this->game->setGameIsOver(1);

            $this->entityManager->persist($this->game);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    /**
     * @return \App\Entity\Answer[]|\Doctrine\Common\Collections\Collection
     */
    public function getAnswersForQuestion()
    {
        return $this->game->getCurrentQuestion()->getAnswers();
    }

    /**
     * @param array $games
     * @param int $userId
     * @return bool
     */
    public function checkUserParticipation(array $games, int $userId): bool
    {
        foreach ($games as $game) {
            if ($game->getUser()->getId() == $userId && $game->getGameIsOver()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $games
     * @return array
     */
    public function sortGames(array $games): array
    {
        usort($games, function ($first, $second) {
            return strcmp($first->getCorrectQuestionsAmount(), $second->getCorrectQuestionsAmount());
        });

        $games = array_reverse($games);

        return $games;
    }

    /**
     * @param array $games
     * @return array|null
     */
    public function getTopUsers(array $games): ?array
    {
        $games = $this->sortGames($games);

        $position = 0;
        $users = [];

        foreach ($games as $game) {
            if ($position == 3) {
                return $users;
            }

            $user = $this
                ->entityManager
                ->getRepository(User::class)
                ->find($game->getUser()->getId());

            $users[$position]['position'] = $position + 1;
            $users[$position]['name'] = $user->getFirstname();
            $users[$position]['secondName'] = $user->getSecondname();
            $users[$position]['surname'] = $user->getSurname();
            $users[$position]['questionAnswered'] = $game->getCorrectQuestionsAmount();
            $position++;
        }
        return $users;
    }

    public function findAllUserGames(int $userId)
    {
        $games=$this
            ->entityManager
            ->getRepository(Game::class)
            ->findBy(['user'=>$userId]);

        return $games;
    }

    /**
     * @param int $userId
     * @param int $quizId
     */
    public function getThisUserResults(int $userId, int $quizId)
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->findOneBy([
                'user_id' => $userId,
                'quiz_id' => $quizId,
            ]);

        $games = $this->findAllByQuizId($quizId);
        $games = $this->sortGames($games);

        $userResult['place'] = $game->getCorrectQuestionsAmount();
    }

    /**
     * @param array $games
     * @param int $userId
     * @return int
     */
    public function getUserPosition(array $games, int $userId)
    {
        $games = $this->sortGames($games);

        $position=0;

        foreach ($games as $game) {
            $position++;
            
            if ($game->getUser()->getId() == $userId) {
                return $position;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * @param mixed $isCorrect
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;
    }
}