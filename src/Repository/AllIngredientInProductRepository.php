<?php

namespace App\Repository;

use App\Entity\AllIngredientInProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllIngredientInProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllIngredientInProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllIngredientInProduct[]    findAll()
 * @method AllIngredientInProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllIngredientInProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllIngredientInProduct::class);
    }
}
