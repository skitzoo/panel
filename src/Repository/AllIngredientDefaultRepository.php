<?php

namespace App\Repository;

use App\Entity\AllIngredientDefault;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllIngredientDefault|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllIngredientDefault|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllIngredientDefault[]    findAll()
 * @method AllIngredientDefault[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllIngredientDefaultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllIngredientDefault::class);
    }
}
