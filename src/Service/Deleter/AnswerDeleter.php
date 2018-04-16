<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 08.04.2018
 * Time: 20:14
 */

namespace App\Service\Deleter;


use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;

class AnswerDeleter
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete(Answer $answer)
    {
        $this->entityManager->remove($answer);
    }
}