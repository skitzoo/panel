<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Common\Persistence\ObjectManager;

class CategorieFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Categorie::class, 9, function(Categorie $categorie, $count) {
            $catlist = ["Sandwichs", "Américains", "Hamburgers", "Paninis", "Frites", "Spécialités", "Menu Enfant", "Sauces", "Boissons"];
            $categorie->setName($catlist[$count]);
            $categorie->setImage('/assets/img/default.jpeg');
            $categorie->setOrdre($count+1);
            $categorie->setLocked(false);
            $categorie->setMenu($count == 0 ? false : true);
        });
        $manager->flush();
    }
}
