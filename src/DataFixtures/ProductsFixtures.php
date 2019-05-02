<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;

class ProductsFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Product::class, 8, function(Product $product, $count) {
            $productlist = ["Saucisse", "Cheddar", "Merguez", "Maxicanto", "Saucisse", "Cheddar", "Merguez", "Maxicanto"];
            $typelist    = ["0", "0", "0", "0", "1", "0", "0", "0"];
            $product->setName($productlist[$count]);
            $product->setImage('/assets/img/default.jpeg');
            $product->setAvailable(true);

            if($count < 4) {
                $product->setCategorie($this->getReference('App\Entity\Categorie_0'));
                $product->setComposed(false);
            } else {
                $product->setCategorie($this->getReference('App\Entity\Categorie_1'));
                $product->setComposed(true);
            }

            $product->setPrice(mt_rand(1*10, 10*10) / 10);
            $product->setType($typelist[$count]);
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategorieFixtures::class,
        );
    }
}
