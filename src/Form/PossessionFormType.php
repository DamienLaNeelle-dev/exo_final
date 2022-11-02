<?php

namespace App\Form;

use App\Entity\Possessions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PossessionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
        ])
        ->add('valeur', MoneyType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Valeur',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
        ])
        ->add('type', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Type',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Ajouter la possession'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Possessions::class,
        ]);
    }
}
