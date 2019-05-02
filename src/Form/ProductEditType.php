<?php

namespace App\Form;

use App\Entity\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom du produit'
            ))

            ->add('image2', FileType::class, array(
                'label' => 'Image',
                'required' => false
            ))
            ->add('price', NumberType::class, array(
                'label' => 'Prix',
                'scale' => 2
            ))
            ->add('available', CheckboxType::class, array(
                'label' => 'En stock',
                'required' => false
            ))
            ->add('categorie')
            ->add('meat', CheckboxType::class, array(
				'label' => 'Afficher cuisson',
				'data' => $options['meat'],
				'required' => false
			))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'name' => null,
            'categorie' => null,
            'price' => null,
            'available' => null,
            'meat' => null,
            'PainDefault' => null,
        ]);
    }
}
