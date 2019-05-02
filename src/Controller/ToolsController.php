<?php

namespace App\Controller;

use App\Repository\AllIngredientRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ToolsController extends AbstractController
{
    /**
     * @Route("/Tools/Pictures", name="Admin_Tools_Pictures")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Pictures(EntityManagerInterface $em, CategorieRepository $categorieRepository, AllIngredientRepository $ingredientRepository, ProductRepository $productRepository)
    {
        $directory = 'assets/uploads/images/';
        $directory_pictures = glob($directory. '*.{jpg,gif,png}', GLOB_BRACE);

        $products_pictures = $productRepository->findAll();
        $categories_pictures = $categorieRepository->findAll();
        $ingredients_pictures = $ingredientRepository->findAll();

        return $this->render('tools/pictures.html.twig', [
            'directory_pictures' => $directory_pictures,
            'products_pictures' => $products_pictures,
            'categories_pictures' => $categories_pictures,
            'ingredients_pictures' => $ingredients_pictures
        ]);
    }
}
