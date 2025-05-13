<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'entreprise',
                    'class' => 'form-control',
                    'autofocus' => true
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez l\'email',
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => '+33 X XX XX XX XX',
                    'class' => 'form-control',
                    'pattern' => '^\+33 [0-9]{1} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}$'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse complète',
                    'class' => 'form-control',
                    'rows' => 3
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez des notes supplémentaires',
                    'class' => 'form-control',
                    'rows' => 3
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Client actif',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'row_attr' => [
                    'class' => 'mb-3 form-check form-switch'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'needs-validation'
            ]
        ]);
    }
} 