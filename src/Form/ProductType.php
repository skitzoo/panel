<?php

namespace App\Form;

use App\Entity\AllIngredient;
use App\Entity\Categorie;
use App\Entity\Ingredient;
use App\Entity\Product;

use App\Repository\AllIngredientRepository;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom du produit',
                'required' => true,
                'data' => $options['name']
            ))

            ->add('image2', FileType::class, array(
                'label' => 'Image',
                'required' => false
            ))
            ->add('meat', CheckboxType::class, array(
                'label' => 'Afficher cuisson',
                'data' => $options['meat'],
                'required' => false
            ))
            ->add('price', NumberType::class, array(
                'label' => 'Prix',
                'required' => true,
                'scale' => 2,
                'data' => $options['price']
            ))
            ->add('available', CheckboxType::class, array(
                'attr' => [
                    'checked' => true
                ],
                'label' => 'En stock',
                'required' => false,
                'data' => $options['available']
            ))
            ->add('categorie', EntityType::class, array(
                'class' => Categorie::class,
                'data' => $options['categorie']
            ))
            ->add('type', ChoiceType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin:5px0;'
                ),
                'choices' => array(
                    'Ajout immédiat'                        => Product::TYPE_GENERAL,
                    'Composition'                           => Product::TYPE_COMPOSED,
                    'Menu'                                  => Product::TYPE_MENU,
                    'Viande'                                => Product::TYPE_MEAT_ALONE,
                    'Tacos (1 viande)'                      => Product::TYPE_ONE_MEAT,
                    'Tacos (2 viandes)'                     => Product::TYPE_TWO_MEAT,
                    'Tacos (3 viandes)'                     => Product::TYPE_THREE_MEAT,
                    'Tacos (4 viandes)'                     => Product::TYPE_FOUR_MEAT,
                    //'Hamburger'                           => Product::TYPE_HAMBURGER,
                    //'Spécialité'                          => Product::TYPE_SPECIALITY,
                    //'Assiette'                            => Product::TYPE_ASSIETTE,
                    //'Panini'                              => Product::TYPE_PANINI,
                    //'Sandwich'                            => Product::TYPE_SANDWICH,
                    //'Américain'                           => Product::TYPE_AMERICAIN
                )
            ))
            ->add('baseall', EntityType::class, array(
                'label' => 'Ingrédient de base 1',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall2', EntityType::class, array(
                'label' => 'Ingrédient de base 2',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall3', EntityType::class, array(
                'label' => 'Ingrédient de base 3',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall4', EntityType::class, array(
                'label' => 'Ingrédient de base 4',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall5', EntityType::class, array(
                'label' => 'Ingrédient de base 5',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall6', EntityType::class, array(
                'label' => 'Ingrédient de base 6',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall7', EntityType::class, array(
                'label' => 'Ingrédient de base 7',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall8', EntityType::class, array(
                'label' => 'Ingrédient de base 8',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall9', EntityType::class, array(
                'label' => 'Ingrédient de base 9',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('baseall10', EntityType::class, array(
                'label' => 'Ingrédient de base 10',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getBaseList();
                }
            ))
            ->add('breadall', EntityType::class, array(
                'label' => 'Pain',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getPainList();
                }
            ))
            ->add('meatall1', EntityType::class, array(
                'label' => 'Viande 1',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getMeatList();
                }
            ))
            ->add('meatall2', EntityType::class, array(
                'label' => 'Viande 2',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getMeatList();
                }
            ))
            ->add('meatall3', EntityType::class, array(
                'label' => 'Viande 3',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getMeatList();
                }
            ))
            ->add('meatall4', EntityType::class, array(
                'label' => 'Viande 4',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getMeatList();
                }
            ))
            ->add('cuissonall', EntityType::class, array(
                'class' => AllIngredient::class,
                'required' => false,
                'label' => 'Cuisson',
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getCuissonList();
                }
            ))
            ->add('sauceall', EntityType::class, array(
                'label' => 'Sauce',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getSauceList();
                }
            ))
            ->add('supplementall', EntityType::class, array(
                'label' => 'Supplément',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getSupplementList();
                }
            ))
            ->add('condimentall', EntityType::class, array(
                'label' => 'Condiment',
                'class' => AllIngredient::class,
                'required' => false,
                'query_builder' => function (AllIngredientRepository $er) {
                    return $er->getCondimentList();
                }
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
