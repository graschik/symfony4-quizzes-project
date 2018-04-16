<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Service\Util\PasswordEncoder;
use App\Service\Util\TokenGenerator;
use App\Service\Util\MessageSender;
use Doctrine\ORM\EntityManagerInterface;

class SignupService
{

    private $entityManager;

    private $passwordEncoder;

    private $tokenGenerator;

    private $messageSender;

    /**
     * SignupService constructor.
     * @param EntityManagerInterface $entityManager
     * @param PasswordEncoder $passwordEncoder
     * @param TokenGenerator $tokenGenerator
     * @param MessageSender $messageSender
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PasswordEncoder $passwordEncoder,
        TokenGenerator $tokenGenerator,
        MessageSender $messageSender
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->messageSender = $messageSender;
    }

    /**
     * @param User $user
     * @return void
     */
    public function signup(User $user): void
    {
        $this->passwordEncoder->encode($user);
        $user->setToken($this->tokenGenerator->generate($user->getEmail()));
        $user->setIsActive(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->messageSender->sendConfirmationMessage($user);
    }
}