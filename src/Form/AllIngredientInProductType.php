<?php

namespace App\Form;

use App\Entity\AllIngredient;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllIngredientInProductType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $list = $this->getList($options);

        $builder
            ->add('ingredient', ChoiceType::class, array(
                'choices' => $list,
                /*'choice_label' => 'name',
                'class' => AllIngredient::class,
                'group_by' => function ($value, $key, $index) {
                    if ($value->getType() == 0)
                        return "Ingrédient de base";
                    elseif ($value->getType() == 1)
                        return "Viande";
                    elseif ($value->getType() == 2)
                        return "Pain";
                    elseif ($value->getType() == 3)
                        return "Sauce";
                    elseif ($value->getType() == 4)
                        return "Cuisson";
                    elseif ($value->getType() == 5)
                        return "Supplément";
                    elseif ($value->getType() == 6)
                        return "Condiment";
                    elseif ($value->getType() == 9)
                        return "Plat";
                    elseif ($value->getType() == 12)
                        return "Non modifiable";
                    elseif ($value->getType() == 14)
                        return "Fromage";
                }*/
            ));
    }

    public function getList($options)
    {
        $er = $this->entityManager->getRepository(AllIngredient::class);
        $dql = $er->getIngredientList($options['product']);
        $list = array();

        /** @var Product $prod */
        $prod = $options['product'];
        $exists = array();
        foreach($prod->getAllIngredientInProducts() as $test)
        {
            $exists[] = $test->getIngredient()->getId();
        }

        /** @var AllIngredient $row */
        foreach($dql->getQuery()->getResult() as $row)
        {
            if(!in_array($row->getId(), $exists))
            {
                $list[$row->getTypeName()] = $row->getId();
            }
        }

        return $list;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'product' => null
        ]);
    }
}
