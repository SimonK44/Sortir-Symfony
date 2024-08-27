<?php

namespace App\Form;

use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDebut', null, [
                'widget' => 'single_text'
            ])
            ->add('duree')
            ->add('dateCloture', null, [
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax')
            ->add('descriptionInfos')
            ->add('etat', EntityType::class, [
                'class' => Etats::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EtatsRepository $EtatRepository) {
                    return $EtatRepository->createQueryBuilder('e')->orderBy('e.libelle', 'ASC');
                }
            ])
            ->add('lieux', EntityType::class, [
                'class' => Lieux::class,
                'choice_label' => 'Lieux',
                'query_builder' => function (LieuxRepository $LieuxRepository) {
                    return $LieuxRepository->createQueryBuilder('l')->orderBy('l.nomLieu', 'ASC');
                }
            ])
            ->add('organisateur', EntityType::class, [
                'class' => Participants::class,
                'choice_label' => 'nom',
                'query_builder' => function (ParticipantsRepository $ParticipantsRepository) {
                return $ParticipantsRepository->createQueryBuilder('p')->orderBy('p.nom', 'ASC');
                }
            ])
            ->add('urlPhoto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
