<?php

declare(strict_types=1);

namespace App\Service\FormHandler;

use App\Service\Creator\QuizCreator;
use App\Service\Util\QuizUtils;
use App\Service\Validator\QuizCreateFormValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuizCreateFormHandler extends AbstractFormHandler
{
    private $creator;

    private $validator;

    private $quizUtils;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuizCreator $creator
     * @param QuizCreateFormValidator $validator
     * @param QuizUtils $quizUtils
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuizCreator $creator,
        QuizCreateFormValidator $validator,
        QuizUtils $quizUtils
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->creator = $creator;
        $this->validator = $validator;
        $this->quizUtils = $quizUtils;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(
        FormInterface $form,
        Request $request,
        array $parameters = []
    ): bool
    {
        if($request->isMethod('POST')){

            $form->submit($request->request->get($form->getName()));
            $data = $form->getData();

            $this->quizUtils->deleteEmptyQuestions($data);

            if($form->isSubmitted() && $form->isValid() && $this->validator->validate($data)){
                $this->creator->create($data);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}