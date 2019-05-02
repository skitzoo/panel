<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('zipcode', NumberType::class, [
                'attr' => [
                    'pattern' => '[0-9]{5}'
                ],
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Numéro de mobile'
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false
            ])
            ->add('birthday', DateType::class, [
                'html5' => true,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
