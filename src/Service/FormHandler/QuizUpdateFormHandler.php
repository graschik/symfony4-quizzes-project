<?php

declare(strict_types=1);

namespace App\Service\FormHandler;

use App\Service\Updater\QuizUpdater;
use App\Service\Util\QuizUtils;
use App\Service\Validator\QuizUpdateFormValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuizUpdateFormHandler extends AbstractFormHandler
{
    private $updater;

    private $validator;

    private $quizUtils;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuizUpdater $updater
     * @param QuizUpdateFormValidator $validator
     * @param QuizUtils $quizUtils
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuizUpdater $updater,
        QuizUpdateFormValidator $validator,
        QuizUtils $quizUtils
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->updater = $updater;
        $this->validator = $validator;
        $this->quizUtils = $quizUtils;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $parameters): bool
    {
        $originalQuestions = new ArrayCollection();
        foreach ($parameters['entity']->getQuestions() as $question) {
            $originalQuestions->add($question);
        }

        if($request->isMethod('POST')){
            $form->submit($request->request->get($form->getName()));
            $data = $form->getData();

            $this->quizUtils->deleteEmptyQuestions($data);

            if($form->isSubmitted() && $form->isValid() && $this->validator->validate($data)){
                $this->updater->update($data, $originalQuestions);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}