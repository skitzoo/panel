<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statusOrder', ChoiceType::class, array(
                'attr' => array('class' => 'form-control',
                    'style' => 'margin:5px0;'
                ),
                'choices' => array(
                    'En cours de commande' => 'En cours de commande',
                    'En attente de paiement' => 'En attente de paiement',
                    'Commande annulée' => 'Commande annulée',
                    'Commande terminée' => 'Commande terminée'
                )
            ))
            ->add('product')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
