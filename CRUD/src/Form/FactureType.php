<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro de facture',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le numéro de facture'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'required' => true,
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'required' => true,
                'scale' => 2,
                'attr' => [
                    'placeholder' => '0.00',
                    'step' => '0.01'
                ]
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'required' => true,
                'choices' => [
                    'Payée' => 'payee',
                    'Partiellement payée' => 'partielle',
                    'Non payée' => 'non_payee'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Entrez une description détaillée'
                ]
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Note',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Ajoutez une note si nécessaire'
                ]
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'label' => 'Client',
                'required' => true,
                'placeholder' => 'Sélectionnez un client'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
} 