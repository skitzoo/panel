<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Common\Persistence\ObjectManager;

class IngredientFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Ingredient::class, 13, function(Ingredient $ingredient, $count) {
            $ingredientlist = ["Pain Blanc", "Steak", "Ketchup", "Salade", "A point", "Pain doré", "Jambon", "Mayonnaise", "Tomate", "Baguette", "Merguez", "Barbecue", "Saucisse"];
            $ingredient->setName($ingredientlist[$count]);
            $ingredient->setPrice(mt_rand(1*10, 10*10) / 10);
            $ingredient->setPicture('/assets/img/default.jpeg');
        });
        $manager->flush();
    }
}
