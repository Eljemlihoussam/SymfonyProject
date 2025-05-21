<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro de facture',
                'attr' => ['placeholder' => 'Entrez le numéro de facture']
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de facturation',
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant (MAD)',
                'currency' => 'MAD',
                'attr' => ['placeholder' => 'Entrez le montant']
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => [
                    'Payée' => 'Payée',
                    'Partiellement payée' => 'Partiellement payée',
                    'Non payée' => 'Non payée'
                ]
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Note',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez une note ou un commentaire']
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'raisonSociale',
                'label' => 'Client',
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
