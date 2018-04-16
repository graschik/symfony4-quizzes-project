<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 08.04.2018
 * Time: 14:38
 */

namespace App\Service\Updater;


use App\Entity\Question;
use App\Service\Util\QuestionUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class QuestionUpdater
{
    private $questionUtils;

    private $entityManager;

    public function __construct(
        QuestionUtils $questionUtils,
        EntityManagerInterface $entityManager
    )
    {
        $this->questionUtils = $questionUtils;
        $this->entityManager = $entityManager;
    }

    public function update(Question $data, ArrayCollection $originalAnswers)
    {
        $this->questionUtils->deleteWhiteSpaces($data);
        $this->questionUtils->addQuestionCharacterIfNotExist($data);

        foreach ($originalAnswers as $answer) {
            if (false === $data->getAnswers()->contains($answer)) {

                $answer->setQuestion(null);

                $this->entityManager->persist($answer);
                $this->entityManager->remove($answer);

            }
        }

        $this->entityManager->flush();
    }
}