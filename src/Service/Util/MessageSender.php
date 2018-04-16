<?php

declare(strict_types=1);

namespace App\Service\Util;


use App\Entity\User;

class MessageSender
{
    private $mailer;

    private $templating;

    /**
     * MessageSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param User $user
     * @return void
     */
    public function sendConfirmationMessage(User $user): void
    {
        $message = (new \Swift_Message('Подтверждение регистрации на Victarin.by'))
            ->setFrom('stasana1998@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig', [
                    'name' => $user->getFirstName() . ' ' . $user->getSurname(),
                    'token' => $user->getToken(),
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * @param User $user
     * @return void
     */
    public function sendRecoveryPasswordMessage(User $user): void
    {
        $message = (new \Swift_Message('Восстановление пароля на Victarin.by'))
        ->setFrom('stasana1998@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->templating->render(
                'emails/recovery_password.html.twig', [
                'token' => $user->getRecoveryToken(),
            ]),
            'text/html'
        );

        $this->mailer->send($message);
    }

}