<?php

namespace App\Form;

use App\Entity\Sites;
use App\Entity\User;
use App\Repository\SitesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('email')
            ->add('site', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nomSite',
                'placeholder' => ' -- Choisir un site -- ',
                'query_builder' => function (SitesRepository $sitesRepository) {
                    return $sitesRepository->createQueryBuilder('s')->orderBy('s.nomSite', 'ASC');
                }
            ])
            ->add('actif', HiddenType::class, [
                'data' => '1', // Valeur par défaut
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type'=> PasswordType::class,
                'options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'invalid_message' => 'les mots de passe ne correspondent pas',
                'first_options' =>[
                    'label'=> 'Mot de Passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new PasswordStrength([
                            'minScore'=> 1,
                            'message'=>'trop faible votre mdp',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label'=>'Confirmez votre Mot de Passe',
                    'invalid_message' => 'Les mdp ne correspondent pas'
                ]
            ])
            ->add('userPhoto', FileType::class, [
                'label' => 'UserPhoto (jpg, jpeg, png, gif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image type (jpg, jpeg, png, gif)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
