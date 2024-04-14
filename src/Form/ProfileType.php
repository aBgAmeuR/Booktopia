<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Utilisateur\Utilisateur;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'constraints' => new NotBlank(),
        'attr' => [
          'class' => 'mt-1 w-full rounded-base text-sm font-bold bg-secondary ring-transparent ring-0 px-4 h-10 focus:outline-none shadow-sm',
        ],
      ])
      ->add('email', EmailType::class, [
        'constraints' => new NotBlank(),
        'attr' => [
          'class' => 'mt-1 w-full rounded-base text-sm font-bold bg-secondary ring-transparent ring-0 px-4 h-10 focus:outline-none shadow-sm',
        ],
      ])
      ->add('currentPassword', PasswordType::class, [
        'mapped' => false,
        'required' => false,
        'attr' => [
          'class' => 'mt-1 w-full rounded-base text-sm font-bold bg-secondary ring-transparent ring-0 px-4 h-10 focus:outline-none shadow-sm',
        ],
      ])
      ->add('newPassword', PasswordType::class, [
        'mapped' => false,
        'required' => false,
        'attr' => [
          'class' => 'mt-1 w-full rounded-base text-sm font-bold bg-secondary ring-transparent ring-0 px-4 h-10 focus:outline-none shadow-sm',
        ],
      ])
      ->add('confirmPassword', PasswordType::class, [
        'mapped' => false,
        'required' => false,
        'attr' => [
          'class' => 'mt-1 w-full rounded-base text-sm font-bold bg-secondary ring-transparent ring-0 px-4 h-10 focus:outline-none shadow-sm',
        ],
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Update',
        'attr' => [
          'class' => 'mt-1 inline-block shrink-0 rounded-md border border-primary bg-primary px-12 py-3 text-sm font-medium text-white transition hover:bg-transparent hover:text-primary focus:outline-none focus:ring active:text-primary',
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Utilisateur::class,
    ]);
  }
}

