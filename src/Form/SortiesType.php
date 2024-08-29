<?php

namespace App\Form;

use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\SitesRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class SortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => 'Nom de la sortie : '])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'widget' => 'single_text'
            ])
            ->add('duree', null,['label' => 'Durée : '])
            ->add('dateCloture', DateTimeType::class, [
                'label' => 'Date limite d\'inscription : ',
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', null, ['label' => 'Nombre de places'])
            ->add('descriptionInfos', TextareaType::class, [
                'label' => 'Description et infos : ',
            ])
            ->add('lieux', EntityType::class, [
                'class' => Lieux::class,
                'choice_label' => 'nomLieu',
                'query_builder' => function (LieuxRepository $lieuxRepository) {
                    return $lieuxRepository->createQueryBuilder('l')->orderBy('l.nomLieu', 'ASC');
                }
            ])
            ->add('isPublished', CheckboxType::class,
                ['required' => false, 'label' => 'Publier la sortie'
            ]);

        $builder
            ->get('lieux')->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $lieu = $form->getData();

                    if ($lieu) {
                        $form->getParent()->add('ville', TextType::class, [
                            'label' => 'Ville : ',
                            'mapped' => false,
                        ]);
                    }
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
