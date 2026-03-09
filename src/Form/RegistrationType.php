<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Last Name'],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Phone Number'],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ],
                'placeholder' => 'Select Gender',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Password'],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Confirm Password'],
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
