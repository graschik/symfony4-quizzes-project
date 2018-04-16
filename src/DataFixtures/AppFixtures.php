<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;

class AppFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $quiz = new Quiz();
            $quiz->setName('Викторина № ' . $i);
            $quiz->setDescription('Очеень длинное описание для викторины №' . $i);

            $answer = new Answer();
            $answer->setText('YEs');
            $answer->setIsCorrect('1');

            $question = new Question();
            $question->setText('Yes?');
            $question->addAnswer($answer);

            $quiz->addQuestion($question);

            $manager->persist($quiz);
        }

        $manager->flush();
    }
}