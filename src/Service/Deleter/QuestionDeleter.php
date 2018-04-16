<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 08.04.2018
 * Time: 18:44
 */

namespace App\Service\Deleter;


use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionDeleter
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete(int $id): bool
    {
        $question = $this
            ->entityManager
            ->getRepository(Question::class)
            ->find($id);

        if(!$question){
            return false;
        }

        $this->entityManager->remove($question);
        $this->entityManager->flush();

        return true;
    }
}