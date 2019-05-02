<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductComposedRemoveController extends AbstractController
{

    /**
     * @Route("/CustomizeRemove", name="Admin_CustomizeRemoveProduct_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_CustomizeProduct(EntityManagerInterface $em, ProductRepository $repository)
    {
        return $this->render('products/customremove_admin.html.twig', [
            'products' => $repository->findBy(['type' => Product::TYPE_COMPOSED_WITH_REMOVE])
        ]);
    }
}
