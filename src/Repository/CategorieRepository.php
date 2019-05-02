<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function getCategoriesAdmin($page, $nbMaxParPage)
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

        $qb = $this->createQueryBuilder('categorie')
            ->orderBy('categorie.ordre', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && $page !== 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        return $paginator;
    }

    public function getCategoriesBorne()
    {
        $qb = $this->createQueryBuilder('categorie')
            ->orderBy('categorie.ordre', 'ASC');

        $query = $qb->getQuery()->execute();

        return $query;
    }
}
