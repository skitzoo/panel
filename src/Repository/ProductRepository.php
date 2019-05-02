<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsAdmin($page, $nbMaxParPage)
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

        $qb = $this->createQueryBuilder('p')
            ->addOrderBy('p.ordre', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        return $paginator;
    }

    public function Stock_filterByNameAsc()
    {
        $sql = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function Stock_filterByNameDesc()
    {
        $sql = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'DESC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function filterByNameAsc($page, $nbMaxParPage)
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

        $sql = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function filterByNameDesc($page, $nbMaxParPage)
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

        $sql = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'DESC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function filterByCategorieAsc($page, $nbMaxParPage)
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

        $sql = $this->createQueryBuilder('p');
        $sql->select(['p'])
            ->innerJoin(Categorie::class, 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.id = p.categorie')
            ->orderBy('c.name', 'ASC');

        $query = $sql->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        if (($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        return $paginator;
    }

    public function Stock_FilterByCatAsc()
    {
        $sql = $this->createQueryBuilder('p');
        $sql->select(['p'])
            ->innerJoin(Categorie::class, 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.id = p.categorie')
            ->orderBy('c.name', 'ASC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function filterByCategorieDesc($page, $nbMaxParPage)
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

        $sql = $this->createQueryBuilder('p');
        $sql->select(['p'])
            ->innerJoin(Categorie::class, 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.id = p.categorie')
            ->orderBy('c.name', 'DESC');

        $query = $sql->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        if (($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException("La page demandée n'existe pas");
        }

        return $paginator;
    }

    public function Stock_FilterByCatDesc()
    {
        $sql = $this->createQueryBuilder('p');
        $sql->select(['p'])
            ->innerJoin(Categorie::class, 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.id = p.categorie')
            ->orderBy('c.name', 'DESC');

        $query = $sql->getQuery()->execute();

        return $query;
    }

    public function SortByCatAndProductName()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categorie', 'categorie')
            ->orderBy('categorie.name', 'asc')
            ->addOrderBy('p.name', 'asc');
    }
}
