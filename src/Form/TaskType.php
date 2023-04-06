<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre de la tâche',
                'attr' => [
                    'placeholder' => 'Entrez le titre de votre tâche'
                ]
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description de la tâche',
                'attr' => [
                    'placeholder' => 'Entrez le contenu de votre tâche'
                ]
            ])

            ->add('Date', DateType::class, [
                'widget' => 'choice',
                'input' => 'datetime'
            ])
            ->add('Ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
