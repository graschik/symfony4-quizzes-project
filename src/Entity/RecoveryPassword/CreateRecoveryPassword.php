<?php

namespace App\Entity\RecoveryPassword;


use Symfony\Component\Validator\Constraints as Assert;

class CreateRecoveryPassword
{
    /**
     * @Assert\NotBlank(
     *     message="Поле пароля должно быть обязательно заполнено"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Пароль не может содержать русские символы"
     * )
     * @Assert\Length(
     *     min="6",
     *     max="255",
     *     minMessage="Пароль должен быть не меньше {{ limit }} символов",
     *     maxMessage="Пароль должен быть не больше {{ limit }} символов",
     * )
     */
    private $plainPassword;

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}