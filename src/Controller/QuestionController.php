<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Question;
use App\Form\Question\QuestionType;
use App\Service\FormHandler\QuestionCreateFormHandler;
use App\Service\FormHandler\QuestionUpdateFormHandler;
use App\Service\Paginator\QuestionPaginator;
use App\Service\Deleter\QuestionDeleter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends Controller
{

    /**
     * @Route("/admin/question/create", name="question.create")
     *
     * @param Request $request
     * @param QuestionCreateFormHandler $questionCreateFormHandler
     * @return Response
     */
    public function createQuestion(
        Request $request,
        QuestionCreateFormHandler $questionCreateFormHandler
    ): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        if($questionCreateFormHandler->handle($form, $request)){
            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create',
            'errors' => $questionCreateFormHandler->getFormErrorMessages()
        ]);
    }

    /**
     * @Route(
     *     "/admin/question/update/{id}",
     *     name="question.update",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param int $id
     * @param Request $request
     * @param QuestionUpdateFormHandler $questionUpdateFormHandler
     * @return Response
     */
    public function updateQuestion(
        int $id,
        Request $request,
        QuestionUpdateFormHandler $questionUpdateFormHandler
    ): Response
    {
        $question = $this
            ->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        if (!$question) {
            throw $this->createNotFoundException('Викторины с индексом ' . $id . ' не существует');
        }

        $form = $this->createForm(QuestionType::class, $question);
        if($questionUpdateFormHandler->handle($form, $request, ['entity' => $question])){
            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_update.html.twig', [
            'form' => $form->createView(),
            'action' => 'update',
            'errors' => $questionUpdateFormHandler->getFormErrorMessages()
        ]);
    }

    /**
     * @Route(
     *     "/admin/question/delete/{id}",
     *      name="question.delete",
     *      requirements={"id"="\d+"}
     * )
     *
     * @param int $id
     * @param QuestionDeleter $questionDeleter
     * @return Response
     */
    public function deleteQuestion(
        int $id,
        QuestionDeleter $questionDeleter
    ): Response
    {
        if(!$questionDeleter->delete($id)){
            throw $this->createNotFoundException('Вопроса с индексом ' . $id . ' не существует ');
        }

        return $this->redirectToRoute('questions.show');
    }

    /**
     * @Route("/admin/questions/show",name="questions.show")
     *
     * @param Request $request
     * @param QuestionPaginator $questionPaginator
     * @return Response
     */
    public function showQuestions(
        Request $request,
        QuestionPaginator $questionPaginator
    ): Response
    {
        return $this->render('questions/questions_show.html.twig', [
            'pagination' => $questionPaginator->createPaginator($request)
        ]);
    }
}