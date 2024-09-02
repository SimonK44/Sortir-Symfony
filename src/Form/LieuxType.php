<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Villes;
use App\Repository\VillesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLieu', TextType::class, [
                'label' => 'Nom du lieu',
            ])
            ->add('rue',TextType::class)
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('ville', EntityType::class, [
                'class' => Villes::class,
                'choice_label' => 'nomVille',
                'query_builder' => function (VillesRepository $VillesRepository) {
                    return $VillesRepository->createQueryBuilder('v')->orderBy('v.nomVille', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieux::class,
        ]);
    }
}
