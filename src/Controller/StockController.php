<?php

namespace App\Controller;

use App\Entity\AllIngredient;
use App\Entity\Product;
use App\Form\StockQuantityType;
use App\Repository\AllIngredientRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @Route("/Stock/Tab/{tab}", name="Admin_GetStock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetStock($tab, AllIngredientRepository $allIngredientRepository, ProductRepository $productRepository, Request $request): Response
    {
        if(is_numeric($tab)) {
            return $this->redirectToRoute('Admin_GetStock', ['tab' => 'products']);
        }

        if ($tab == "products")
        {
            /*$product = new Product();

            $form = $this->createForm(StockQuantityType::class, $product);
            $form->handleRequest($request);

            $form = $this->createFormBuilder()
                ->add('quantity', IntegerType::class)
                ->getForm();*/

            //if ($form->isSubmitted() && $form->isValid())
            //{

            //}

            return $this->render('stock/products.html.twig', [
                //'form' => $form->createView(),
                'products' => $productRepository->findAll(),
                'title' => 'Gestion de stock',
            ]);
        }
        else
        {
            return $this->render('stock/ingredients.html.twig', [
                'ingredients' => $allIngredientRepository->findBy(['Type' => [AllIngredient::TYPE_MEAT,AllIngredient::TYPE_BREAD,AllIngredient::TYPE_SAUCE,AllIngredient::TYPE_SUPPLEMENT,AllIngredient::TYPE_PLAT,AllIngredient::TYPE_CHEESE]]),
                'title' => 'Gestion de stock',
            ]);
        }
    }

    /**
     * @Route("/Stock/{id}/AddIngredient", name="Admin_AddIngredientToStock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AddIngredientInStock($id, AllIngredient $allIngredient, EntityManagerInterface $em): Response
    {
        $allIngredient->setAvailable(true);
        $em->flush();
        return $this->redirectToRoute('Admin_GetStock', ['tab' => 'ingredients']);
    }

    /**
     * @Route("/Stock/{id}/RemoveIngredient", name="Admin_RemoveIngredientToStock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_RemoveIngredientInStock($id, AllIngredient $allIngredient, EntityManagerInterface $em): Response
    {
        $allIngredient->setAvailable(false);
        $em->flush();
        return $this->redirectToRoute('Admin_GetStock', ['tab' => 'ingredients']);
    }

    /**
     * @Route("/Stock/{id}/Add", name="Admin_AddProductToStock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AddInStock($id, Product $product, EntityManagerInterface $em): Response
    {
        $product->setAvailable(true);
        $em->flush();
        return $this->redirectToRoute('Admin_GetStock', ['tab' => 'products']);
    }

    /**
     * @Route("/Stock/{id}/Remove", name="Admin_RemoveProductToStock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_RemoveProductToStock($id, Product $product, EntityManagerInterface $em): Response
    {
        $product->setAvailable(false);
        $em->flush();
        return $this->redirectToRoute('Admin_GetStock', ['tab' => 'products']);
    }

    /**
     * @Route("/Stock/Tab/products/FilterByNameAsc", name="Admin_StockFilterByNameAsc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_StockFilterByNameAsc(ProductRepository $productRepository): Response
    {
        $products = $productRepository->Stock_filterByNameAsc();

        return $this->render('stock/products.html.twig', [
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Stock/Tab/products/FilterByNameDesc", name="Admin_StockFilterByNameDesc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_StockFilterByNameDesc(ProductRepository $productRepository): Response
    {
        $products = $productRepository->Stock_filterByNameDesc();

        return $this->render('stock/products.html.twig', [
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Stock/Tab/products/FilterByCatAsc", name="Admin_StockFilterByCatAsc")
     */
    public function Admin_StockFilterByCatAsc(ProductRepository $productRepository): Response
    {
        $products = $productRepository->Stock_FilterByCatAsc();

        return $this->render('stock/products.html.twig', [
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }
    /**
     * @Route("/Stock/Tab/products/FilterByCatDesc", name="Admin_StockFilterByCatDesc")
     */
    public function Admin_StockFilterByCatDesc(ProductRepository $productRepository): Response
    {
        $products = $productRepository->Stock_FilterByCatDesc();

        return $this->render('stock/products.html.twig', [
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }
    /**
     * @Route("/Stock/Tab/ingredients/FilterByNameAsc", name="Admin_StockIngredientsFilterByNameAsc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_StockIngredientsFilterByNameAsc(AllIngredientRepository $allIngredientRepository): Response
    {
        $ingredients = $allIngredientRepository->Stock_FilterByNameAsc();

        return $this->render('stock/ingredients.html.twig', [
            'ingredients' => $ingredients,
            'title' => 'Liste des ingrédients'
        ]);
    }

    /**
     * @Route("/Stock/Tab/ingredients/FilterByNameDesc", name="Admin_StockIngredientsFilterByNameDesc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_StockIngredientsFilterByNameDesc(AllIngredientRepository $allIngredientRepository): Response
    {
        $ingredients = $allIngredientRepository->Stock_FilterByNameDesc();

        return $this->render('stock/ingredients.html.twig', [
            'ingredients' => $ingredients,
            'title' => 'Liste des ingrédients'
        ]);
    }

}
