<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            // ->add('roles')
            ->add('password', PasswordType::class)
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('dateCreated', DateType::class)
            ->add('dateFinish')
            ->add('tasks', EntityType::class, [
                'class' => Task::class, // Remplacez par la classe Task appropriée
                'choice_label' => 'name', // Remplacez par le champ approprié à afficher dans la liste déroulante
                'multiple' => true, // Si l'utilisateur peut avoir plusieurs tâches
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-inline'],
                // Si vous souhaitez une liste déroulante avec des cases à cocher
            ])
            ->add('contractType')
            ->add(
                'sector',
                EntityType::class,
                [
                    'class'         => Sector::class,
                    'choice_label'  => 'name'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
