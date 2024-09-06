<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Repository\LieuxRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
            'label' => 'Nom de la sortie',
            'required' => false
            ])
            ->add('dateDebut', DateTimeType::class, [
                'required' => false,
                'label' => 'Entre',
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateTimeType::class, [
                'required' => false,
                'label' => 'Et',
                'widget' => 'single_text',
            ])
            ->add('CheckOrga', CheckboxType::class, [
                'required' => false,
                'label' => 'Je suis organisateur/trice'
                ])
            ->add('CheckInscript', CheckboxType::class, [
                'required' => false,
                'label' => 'Je suis inscrit/e'
            ])
            ->add('CheckPasInscript', CheckboxType::class, [
                'required' => false,
                'label' => 'Je ne suis pas inscrit/e'
            ])
            ->add('CheckPasse', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie passées'
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Rechercher',
                'attr'=> [
                    'class' => 'button_filtre']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
