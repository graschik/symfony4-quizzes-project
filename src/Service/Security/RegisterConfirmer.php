<?php

declare(strict_types=1);

namespace App\Service\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegisterConfirmer
{

    private $entityManager;

    /**
     * RegisterConfirmer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return void
     */
    public function confirm(User $user): void
    {
        $user->setIsActive(true);
        $user->setToken('');
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}