<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('invoiceNumber', TextType::class, [
                'label' => 'Numéro de facture',
                'attr' => ['class' => 'form-control']
            ])
            ->add('invoiceDate', DateType::class, [
                'label' => 'Date de facturation',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Montant (MAD)',
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'État de la facture',
                'choices' => [
                    'Non payée' => Invoice::STATUS_UNPAID,
                    'Partiellement payée' => Invoice::STATUS_PARTIALLY_PAID,
                    'Payée' => Invoice::STATUS_PAID,
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3
                ]
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'companyName',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('c.companyName', 'ASC');
                },
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'user' => null,
        ]);

        $resolver->setRequired(['user']);
        $resolver->setAllowedTypes('user', User::class);
    }
} 