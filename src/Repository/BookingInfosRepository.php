<?php

namespace App\Repository;

use App\Entity\BookingInfos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method BookingInfos|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingInfos|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingInfos[]    findAll()
 * @method BookingInfos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingInfosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookingInfos::class);
    }

    public function getBookings($page, $nbMaxParPage)
    {
        if (!is_numeric($page)) {
            throw new \InvalidArgumentException("La valeur page est incorrecte");
        }

        if ($page < 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        if (!is_numeric($nbMaxParPage)) {
            throw new \InvalidArgumentException("La valeur nbMaxParPage est incorrecte");
        }

        $qb = $this->createQueryBuilder('booking_infos')
            ->orderBy('booking_infos.id', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        return $paginator;
    }

    // /**
    //  * @return BookingInfos[] Returns an array of BookingInfos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookingInfos
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
