<?php

namespace App\Repository;

use App\Entity\CardAllLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardAllLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardAllLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardAllLine[]    findAll()
 * @method CardAllLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardAllLineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardAllLine::class);
    }

//    /**
//     * @return CardAllLine[] Returns an array of CardAllLine objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardAllLine
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
