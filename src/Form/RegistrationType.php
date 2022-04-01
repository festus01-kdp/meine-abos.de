<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-Mail',
                'attr' => ['placeholder' => 'E-Mail'
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Passwort',
                'attr' => [
                    'placeholder' => 'Passwort'
                ]
            ])
            ->add('registrieren', SubmitType::class, array('label' => 'Registrieren'));;
    }

}
