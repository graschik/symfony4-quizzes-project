<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 08.04.2018
 * Time: 14:38
 */

namespace App\Service\Updater;


use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class QuizUpdater
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(Quiz $data, ArrayCollection $originalQuestions)
    {
        foreach($data->getQuestions() as $question){
            if($question->getText() == null || $question->getText() == ""){
                $data->getQuestions()->removeElement($question);
            }
        }

        foreach($data->getQuestions() as $question){
            $isExist = false;

            foreach ($originalQuestions as $originalQuestion)
                if($question->getText() == $originalQuestion->getText()){
                    $isExist = true;
                    break;
                }

            if(!$isExist){
                $data->addQuestion($this
                    ->entityManager
                    ->getRepository(Question::class)
                    ->findOneBy(['text' => $question->getText()])
                );
                $data->removeQuestion($question);
            }
        }

        $this->entityManager->flush($data);
    }
}