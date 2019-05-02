<?php

namespace App\Form;

use App\Entity\AllIngredient;
use App\Repository\AllIngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;

class AllIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('picture2', FileType::class, array(
                'label' => 'Image',
                'required' => false
            ))
            ->add('Type', ChoiceType::class, array(
                'attr' => array('class' => 'form-control',
                    'style' => 'margin:5px0;'
                ),
                'choices' => array(
                    'Général' => array(
                        'Ingrédient de base'    => AllIngredient::TYPE_REMOVE,
                        'Fromage'               => AllIngredient::TYPE_CHEESE,
                        'Viande'                => AllIngredient::TYPE_MEAT,
                        'Pain'                  => AllIngredient::TYPE_BREAD,
                        'Sauce'                 => AllIngredient::TYPE_SAUCE,
                        'Cuisson'               => AllIngredient::TYPE_CUISSON,
                        'Supplément'            => AllIngredient::TYPE_SUPPLEMENT,
                        'Condiment'             => AllIngredient::TYPE_CONDIMENT,
                        'Non modifiable'        => AllIngredient::TYPE_UNCHANGEABLE
                    ),
                    'Menu' => array(
                        'Plat'                  => AllIngredient::TYPE_PLAT
                    )
                )
            ))
            ->add('price', NumberType::class, array(
                'label' => 'Prix',
                'required' => true,
                'scale' => 2
            ))
            ->add('available', CheckboxType::class, array(
                'label' => 'En stock',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AllIngredient::class,
        ]);
    }
}
