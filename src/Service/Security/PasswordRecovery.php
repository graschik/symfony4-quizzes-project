<?php

declare(strict_types=1);

namespace App\Service\Security;


use App\Entity\RecoveryPassword\CreateEmailRequest;
use App\Entity\RecoveryPassword\CreateRecoveryPassword;
use App\Entity\User;
use App\Service\Util\MessageSender;
use App\Service\Util\PasswordEncoder;
use App\Service\Util\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

class PasswordRecovery
{

    private $entityManager;

    private $tokenGenerator;

    private $messageSender;

    private $passwordEncoder;

    /**
     * PasswordRecovery constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenGenerator $tokenGenerator
     * @param MessageSender $messageSender
     * @param PasswordEncoder $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenGenerator $tokenGenerator,
        MessageSender $messageSender,
        PasswordEncoder $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->messageSender = $messageSender;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $data
     * @return bool
     */
    public function startRecovery(CreateEmailRequest $data): bool
    {
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $data->getEmail()]);

        if ($user) {
            $user->setRecoveryToken($this->tokenGenerator->generate($user->getPassword()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->messageSender->sendRecoveryPasswordMessage($user);
        }

        return true;
    }

    /**
     * @param CreateRecoveryPassword $data
     * @param User $user
     * @return void
     */
    public function finishRecovery(CreateRecoveryPassword $data, User $user): void
    {
        $user->setPlainPassword($data->getPlainPassword());

        $this->passwordEncoder->encode($user);
        $user->setRecoveryToken('');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}