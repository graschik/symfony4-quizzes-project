<?php

namespace App\Entity\RecoveryPassword;

use Symfony\Component\Validator\Constraints as Assert;

class CreateEmailRequest
{
    /**
     * @Assert\NotBlank(
     *     message="Поле должно быть заполнено"
     * )
     * @Assert\Email(
     *     message="Поле должно соответсвовать формату электронной почты"
     * )
     */
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}