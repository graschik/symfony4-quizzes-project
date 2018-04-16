<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 09.04.2018
 * Time: 3:04
 */

namespace App\Service\Util;


use App\Entity\Quiz;

class QuizUtils
{
    public function deleteEmptyQuestions(Quiz $data)
    {
        foreach ($data->getQuestions() as $question){
            if($question->getText() == null && $question->getText() == ''){
                $data->getQuestions()->removeElement($question);
            }
        }
    }
}