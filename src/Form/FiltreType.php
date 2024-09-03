<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Repository\LieuxRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            'label' => 'Nom de la sortie contient : ',
            'required' => false
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Entre : ',
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Et : ',
                'widget' => 'single_text'
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Rechercher'
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
