<?php

declare(strict_types=1);

namespace App\Service\FormHandler;

use App\Service\Creator\QuestionCreator;
use App\Service\Validator\QuestionCreateFormValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuestionCreateFormHandler extends AbstractFormHandler
{
    private $validator;

    private $creator;

    /**
     * QuestionCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuestionCreator $creator
     * @param QuestionCreateFormValidator $validator
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuestionCreator $creator,
        QuestionCreateFormValidator $validator
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->creator = $creator;
        $this->validator = $validator;
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
        $form->handleRequest($request);
        $question = $form->getData();

        if($form->isSubmitted()){

            if($form->isValid() && $this->validator->validate($question)){
                $this->creator->create($question);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}