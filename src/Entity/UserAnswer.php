<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class UserAnswer
{
    /**
     * @Assert\Range(
     *     min=1,
     *     minMessage="Ответ должен быть больше нуля"
     * )
     */
    private $number;

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }
}