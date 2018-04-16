<?php

namespace App\Service\QuizService;

use Doctrine\ORM\EntityManagerInterface;

class QuizService
{

    private $entityManager;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}