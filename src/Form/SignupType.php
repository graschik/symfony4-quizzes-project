<?php

declare(strict_types=1);

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('secondname', TextType::class, [
                'label' => 'Отчество',
            ])
            ->add('username', TextType::class, [
                'label' => 'Логин'
            ])
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Пароль'
                ],
                'second_options' => [
                    'label' => 'Повторите пароль'
                ],
            ])
            ->add('email', EmailType::class, [
                'label'    => 'Электронная почта'
            ])
            ->add('confirm', SubmitType::class, [
                'label' => 'Зарегистрироваться'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'cascade_validation' => true,
        ]);
    }
}