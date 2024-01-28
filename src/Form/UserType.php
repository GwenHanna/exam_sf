<?php

namespace App\Form;

use App\Entity\ContractType;
use App\Entity\Media;
use App\Entity\Sector;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                ]
            )
            // ->add('roles')
            ->add(
                'password',
                PasswordType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'dateFinish',
                DateType::class,
                [
                    'label' => 'Date de fin',
                ]
            )
            ->add('tasks', EntityType::class, [
                'label' => "Secteur d'activité",
                'class' => Task::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-inline'],

            ])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'label' => 'Type de contrats',
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
                'required' => true,
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
