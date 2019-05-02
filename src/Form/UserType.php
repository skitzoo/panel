<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                "label" => "Nom de compte"
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe')
            ))
            ->add('roles', ChoiceType::class, array(
                'attr' => array('class' => 'form-control',
                    'style' => 'margin:5px0;'
                ),
                'choices' => array(
                    'Administrateur' => 'ROLE_ADMIN',
                    'Hôte' => 'ROLE_HOTE'
                ),
                'preferred_choices' => array('ROLE_ADMIN'),
                'multiple' => true,
                'required' => true
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adresse Mail',
                'required' => false
            ))
            ->add('isActive', CheckboxType::class, array(
                'label' => 'Actif',
                'required' => false
            ))
            ->add('isBlocked', CheckboxType::class, array(
                'label' => 'Bloqué',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
