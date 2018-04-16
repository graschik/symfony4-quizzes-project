<?php

declare(strict_types=1);

namespace App\Service\FormHandler;


use App\Entity\User;
use App\Service\Security\PasswordRecovery;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class StartPasswordRecoveryFormHandler extends AbstractFormHandler
{
    private $passwordRecovery;

    /**
     * StartPasswordRecoveryFormHandler constructor.
     * @param PasswordRecovery $passwordRecovery
     */
    public function __construct(PasswordRecovery $passwordRecovery)
    {
        $this->passwordRecovery = $passwordRecovery;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->passwordRecovery->startRecovery($data);

            return true;
        }

        return false;
    }


}