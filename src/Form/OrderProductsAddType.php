<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderProductsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'query_builder' => function (ProductRepository $productRepository) {
                return $productRepository->SortByCatAndProductName();
                },
                'group_by' => function ($value, $key, $index) {
                return $value->getCategorie()->getName();
                }
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
