<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\QuizActivateService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizActivateController extends Controller
{
    private $quizActivateService;

    /**
     * QuizActivateController constructor.
     * @param QuizActivateService $quizActivateService
     */
    public function __construct(QuizActivateService $quizActivateService)
    {
        $this->quizActivateService = $quizActivateService;
    }

    /**
     * @Route(
     *     "/admin/quiz/activate/{id}",
     *     name="quiz.activate",
     *     requirements={"id"="\d+"}
     * )
     * @param int $id
     * @return Response
     */
    public function activateQuiz(int $id): Response
    {
        $this->quizActivateService->activate($id);

        return $this->redirectToRoute('quiz.show');
    }

    /**
     * @Route(
     *     "/admin/quiz/disactivate/{id}",
     *     name="quiz.disactivate",
     *     requirements={"id"="\d+"}
     * )
     * @param int $id
     * @return Response
     */
    public function disactivateQuiz(int $id): Response
    {
        $this->quizActivateService->disactivate($id);

        return $this->redirectToRoute('quiz.show');
    }
}