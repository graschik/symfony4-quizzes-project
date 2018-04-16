<?php

declare(strict_types=1);

namespace App\Form;


use App\Entity\Quiz;
use App\Form\Question\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuizType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название викторины',
                'attr' => [
                    'placeholder' => 'Название'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание викторины',
                'attr' => [
                    'placeholder' => 'Описание'
                ]
            ])
            ->add('questions', CollectionType::class, [
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'entry_type' => QuestionType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-cotrol',
                    ],
                ],
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Сохранить викторину'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}