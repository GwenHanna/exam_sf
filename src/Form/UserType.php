<?php

namespace App\Form;

use App\Entity\ContractType;
use App\Entity\Media;
use App\Entity\Sector;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('dateFinish', DateType::class)
            ->add('tasks', EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-inline'],

            ])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de selectionner un contrat',
                    ]),
                ],
            ])
            ->add(
                'sector',
                EntityType::class,
                [
                    'class' => Sector::class,
                    'choice_label' => 'name',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de selectionner un secteur',
                        ]),
                    ],
                ]

            )
            ->add('filename', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Merci de selectionner un format valide',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
