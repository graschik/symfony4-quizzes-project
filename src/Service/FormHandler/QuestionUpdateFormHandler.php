<?php

declare(strict_types=1);

namespace App\Service\FormHandler;

use App\Service\Updater\QuestionUpdater;
use App\Service\Util\QuestionUtils;
use App\Service\Validator\QuestionUpdateFormValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuestionUpdateFormHandler extends AbstractFormHandler
{
    private $updater;

    private $validator;

    private $questionUtils;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuestionUpdater $updater
     * @param QuestionUpdateFormValidator $validator
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuestionUpdater $updater,
        QuestionUpdateFormValidator $validator,
        QuestionUtils $questionUtils
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->updater = $updater;
        $this->validator = $validator;
        $this->questionUtils = $questionUtils;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $parameters): bool
    {
        $originalAnswers = new ArrayCollection();
        foreach ($parameters['entity']->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $this->questionUtils->deleteEmptyAnswers($parameters['entity']);

            if($this->validator->validate($parameters['entity'])){
                $this->updater->update($parameters['entity'], $originalAnswers);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}