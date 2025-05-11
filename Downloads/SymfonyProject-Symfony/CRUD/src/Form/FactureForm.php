<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro de facture',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de facturation',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant de la facture (MAD)',
                'currency' => 'MAD',
                'attr' => ['class' => 'form-control'],
                'divisor' => 1,
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État de la facture',
                'choices' => [
                    'Payée' => 'payee',
                    'Partiellement payée' => 'partielle',
                    'Non payée' => 'non_payee',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'companyName', // ou un champ parlant de l'entité Client
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $user);
                },
                'label' => 'Client',
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'user' => null,
        ]);
    }
}
