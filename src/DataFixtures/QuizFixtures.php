<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {


            $quiz = new Quiz();
            $quiz->setName('Викторина № '.$i);
            $quiz->setDescription('Очеень длинное описание для викторины №'.$i);
            //$quiz->addQuestion(new Question());

            $answer=new Answer();
            $answer->setText('YEs');
            $answer->setIsCorrect('1');

            $question=new Question();
            $question->setText('Yes?');
            $question->addAnswer($answer);

            $quiz->addQuestion($question);

            $manager->persist($quiz);
        }

        $manager->flush();
    }
}