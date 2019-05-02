<?php

namespace App\Form;

use App\Entity\BookingInfos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('scheduleAt', ChoiceType::class, [
            'choices' => $options['hours'],
            'choice_label' => function($value) {
                return $value;
            },
            'choice_value' => function($choice) {
                return $choice;
            },
            'label' => 'Horaire'
        ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookingInfos::class,
            'hours' => [],
        ]);
    }
}
