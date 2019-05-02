<?php

namespace App\Repository;

use App\Entity\AllIngredient;
use App\Entity\AllIngredientDefault;
use App\Entity\Categorie;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllIngredient[]    findAll()
 * @method AllIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllIngredientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllIngredient::class);
    }

    public function getBaseList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', [AllIngredient::TYPE_REMOVE,AllIngredient::TYPE_UNCHANGEABLE]);
    }

    public function getPainList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_BREAD);
    }

    public function getMeatList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_MEAT);
    }

    public function getCuissonList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_CUISSON);
    }

    public function getSauceList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_SAUCE);
    }

    public function getSupplementList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_SUPPLEMENT);
    }

    public function getCondimentList()
    {
        return $this->createQueryBuilder('ingredient')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', AllIngredient::TYPE_CONDIMENT);
    }

    public function getIngredientList(Product $product)
    {
        $sql = $this->createQueryBuilder('ingredient')
            ->orderBy('ingredient.Type', 'ASC')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', [AllIngredient::TYPE_REMOVE, AllIngredient::TYPE_MEAT, AllIngredient::TYPE_BREAD, AllIngredient::TYPE_SAUCE, AllIngredient::TYPE_CUISSON, AllIngredient::TYPE_SUPPLEMENT, AllIngredient::TYPE_CONDIMENT, AllIngredient::TYPE_PLAT, AllIngredient::TYPE_UNCHANGEABLE, AllIngredient::TYPE_CHEESE]);

        return $sql;
    }

    public function getIngredients()
    {
        return $this->createQueryBuilder('ingredient')
            ->orderBy('ingredient.Type', 'ASC')
            ->addOrderBy('ingredient.ordre', 'ASC')
            ->getQuery()->execute();
    }

    public function Stock_FilterByNameAsc()
    {
        $sql = $this->createQueryBuilder('ingredient')
            ->orderBy('ingredient.Name', 'ASC')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', [AllIngredient::TYPE_MEAT,AllIngredient::TYPE_BREAD,AllIngredient::TYPE_SAUCE,AllIngredient::TYPE_SUPPLEMENT,AllIngredient::TYPE_PLAT,AllIngredient::TYPE_CHEESE]);

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function Stock_FilterByNameDesc()
    {
        $sql = $this->createQueryBuilder('ingredient')
            ->orderBy('ingredient.Name', 'DESC')
            ->where("ingredient.Type in (:ingredient)")
            ->setParameter('ingredient', [AllIngredient::TYPE_MEAT,AllIngredient::TYPE_BREAD,AllIngredient::TYPE_SAUCE,AllIngredient::TYPE_SUPPLEMENT,AllIngredient::TYPE_PLAT,AllIngredient::TYPE_CHEESE]);

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function SortByTypeAndIngredientName()
    {
        return $this->createQueryBuilder('ai')
            ->orderBy('ai.Type', 'asc')
            ->addOrderBy('ai.Name', 'asc');
    }
}
