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

//    /**
//     * @return TacosIngredientDefault[] Returns an array of TacosIngredientDefault objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TacosIngredientDefault
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
