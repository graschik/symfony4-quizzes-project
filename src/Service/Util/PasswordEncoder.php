<?php

declare(strict_types=1);

namespace App\Service\Util;


use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoder
{
    private $passwordEncoder;

    /**
     * PasswordEncoder constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @return void
     */
    public function encode(User $user): void
    {
        $password = $this
            ->passwordEncoder
            ->encodePassword($user, $user->getPlainPassword());

        $user->setPassword($password);
    }
}