<?php

namespace App\Form;

use App\Entity\ContractType;
use App\Entity\Sector;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Repository\ContractTypeRepository;
use App\Repository\SectorRepository;

class RegistrationFormType extends AbstractType
{
    public function __construct(
        private ContractTypeRepository $contractTypeRepository,
        private SectorRepository $sectorRepository
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sectors = $this->sectorRepository->findAll();
        $contracts = $this->contractTypeRepository->findAll();
        $builder
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label_format' => 'Prénom',
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label_format' => 'Nom',
                ]
            )
            ->add('sector', ChoiceType::class, [

                'label_format' => "Secteur d'activité",

                'choices' => [
                    $sectors
                ],
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                'choice_value' => 'name',
                // // a callback to return the label for a given choice
                // // if a placeholder is used, its empty value (null) may be passed but
                // // its label is defined by its own "placeholder" option
                'choice_label' => function (?Sector $sector): string {
                    return $sector ? strtoupper($sector->getName()) : '';
                },
                // // // returns the html attributes for each option input (may be radio/checkbox)
                'choice_attr' => function (?Sector $sector): array {
                    return $sector ? ['class' => 'category_' . strtolower($sector->getName())] : [];
                },
                // // // every option can use a string property path or any callable that get
                // // // passed each choice as argument, but it may not be needed
                // 'group_by' => function (): string {
                //     // randomly assign things into 2 groups
                //     return rand(0, 1) === 1 ? 'Group A' : 'Group B';
                // },
                // // a callback to return whether a category is preferred
                // 'preferred_choices' => function (?Category $category): bool {
                //     return $category && 100 < $category->getArticleCounts();
                // },
            ])

            ->add('contractType', ChoiceType::class, [

                'label_format' => 'Type de contrats',

                'choices' => [
                    $contracts
                ],
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                // 'choice_value' => 'name',
                // // a callback to return the label for a given choice
                // // if a placeholder is used, its empty value (null) may be passed but
                // // its label is defined by its own "placeholder" option
                'choice_label' => function (ContractType $contract): string {
                    return $contract ? strtoupper($contract->getName()) : '';
                },
                // // returns the html attributes for each option input (may be radio/checkbox)
                // 'choice_attr' => function (?Category $category): array {
                //     return $category ? ['class' => 'category_' . strtolower($category->getName())] : [];
                // },
                // // every option can use a string property path or any callable that get
                // // passed each choice as argument, but it may not be needed
                // 'group_by' => function (): string {
                //     // randomly assign things into 2 groups
                //     return rand(0, 1) === 1 ? 'Group A' : 'Group B';
                // },
                // // a callback to return whether a category is preferred
                // 'preferred_choices' => function (?Category $category): bool {
                //     return $category && 100 < $category->getArticleCounts();
                // },
            ])

            // ->add('contractType.name', TextType::class)
            // ->add('sector')
            // ->add('media', TextType::class)
            ->add('plainPassword', PasswordType::class, [
                'label_format' => 'Mot de passe',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le champ du mot de passe ne peut être vide !",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir 6  caractère minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
