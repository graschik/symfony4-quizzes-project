<?php

declare(strict_types=1);

namespace App\Service\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{
    protected $isFormValid;

    protected $formErrorMessages;

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    abstract public function handle(
        FormInterface $form,
        Request $request,
        array $parameters
    ): bool;

    /**
     * @return bool
     */
    public function getIsFormValid(): bool
    {
        return $this->isFormValid;
    }

    /**
     * @param bool $isFormValid
     * @return void
     */
    public function setIsFormValid(bool $isFormValid): void
    {
        $this->isFormValid = $isFormValid;
    }

    /**
     * @return array||null
     */
    public function getFormErrorMessages(): ?array
    {
        return $this->formErrorMessages;
    }

    /**
     * @param array $formErrorMessages
     * @return void
     */
    public function setFormErrorMessages(array $formErrorMessages): void
    {
        $this->formErrorMessages = $formErrorMessages;
    }

    /**
     * @param string $message
     * @return void
     */
    public function addErrorMessage(string $message): void
    {
        $this->formErrorMessages[] = $message;
    }

}