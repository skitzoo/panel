<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function getByStatus($status)
    {
        return $this->createQueryBuilder('orders')
            ->andWhere('orders.status = :status')
            ->setParameter('status', $status)
            ->orderBy('orders.status', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function getPrepareOrders()
    {
        return $this->createQueryBuilder('orders')
            ->andWhere('orders.status = :status')
            ->setParameter('status', 'Commande en préparation')
            ->orderBy('orders.status', 'ASC')
            ->setMaxResults('8')
            ->getQuery()
            ->execute();
    }

    public function resetIncrement()
    {
        return $connection = $this->getEntityManager()->getConnection()
            ->exec('ALTER TABLE orders AUTO_INCREMENT = 1');
    }
}
