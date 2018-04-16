<?php

declare(strict_types=1);

namespace App\Service\FormHandler;


use App\Service\Security\SignupService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SignupFormHandler extends AbstractFormHandler
{
    private $signupService;

    /**
     * SignupFormHandler constructor.
     * @param SignupService $signupService
     */
    public function __construct(SignupService $signupService)
    {
        $this->signupService = $signupService;
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
            $user = $form->getData();
            $this->signupService->signup($user);

            return true;
        }

        return false;
    }


}