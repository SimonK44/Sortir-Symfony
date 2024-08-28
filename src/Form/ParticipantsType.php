<?php

namespace App\Form;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Repository\SitesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('mail')
            ->add('motDePasse')
            ->add('administrateur')
            ->add('actif')
            ->add('site', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nomSite',
                'query_builder' => function (SitesRepository $sitesRepository) {
                    return $sitesRepository->createQueryBuilder('s')->orderBy('s.nomSite', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
