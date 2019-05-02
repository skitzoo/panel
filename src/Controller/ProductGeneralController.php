<?php

namespace App\Controller;

use App\Entity\AllIngredient;
use App\Entity\AllIngredientInProduct;
use App\Entity\Product;
use App\Form\AllIngredientInProductType;
use App\Form\ProductEditType;
use App\Form\ProductType;
use App\Repository\AllIngredientInProductRepository;
use App\Repository\AllIngredientRepository;
use App\Repository\ProductRepository;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductGeneralController extends AbstractController
{

    /**
     * @Route("/Products/Page/{page}", name="Admin_GetAllProducts", requirements={"page" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $page
     * @return array
     */
    public function Admin_GetAllProducts($page, ProductRepository $productRepository): Response
    {
        $productsPerPage = 25;

        $products = $productRepository->getProductsAdmin($page, $productsPerPage);

        $pagination = [
            'totalPages' => ceil(count($products) / $productsPerPage),
            'page' => $page
        ];

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Products/FilterByCatAsc/{page}", name="Admin_FilterByCatAsc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_FilterByCatAsc($page, ProductRepository $productRepository): Response
    {
        $productsPerPage = 25;
        $products = $productRepository->filterByCategorieAsc($page, $productsPerPage);

        $pagination = [
            'totalPages' => ceil(count($products) / $productsPerPage),
            'page' => $page
        ];

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Products/FilterByCatDesc/{page}", name="Admin_FilterByCatDesc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_FilterByCatDesc($page, ProductRepository $productRepository): Response
    {
        $productsPerPage = 25;
        $products = $productRepository->filterByCategorieDesc($page, $productsPerPage);

        $pagination = [
            'totalPages' => ceil(count($products) / $productsPerPage),
            'page' => $page
        ];

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Products/FilterByNameAsc/{page}", name="Admin_FilterByNameAsc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_FilterByNameAsc($page, ProductRepository $productRepository): Response
    {
        $productsPerPage = 25;
        $products = $productRepository->filterByNameAsc($page, $productsPerPage);

        $pagination = [
            'totalPages' => ceil(count($products) / $productsPerPage),
            'page' => $page
        ];

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Products/FilterByNameDesc/{page}", name="Admin_FilterByNameDesc")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_FilterByNameDesc($page, ProductRepository $productRepository): Response
    {
        $productsPerPage = 10;
        $products = $productRepository->filterByNameDesc($page, $productsPerPage);

        $pagination = [
            'totalPages' => ceil(count($products) / $productsPerPage),
            'page' => $page
        ];

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
            'products' => $products,
            'title' => 'Liste des produits'
        ]);
    }

    /**
     * @Route("/Products/New", name="Admin_AddProduct")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AddProduct(Request $request, EntityManagerInterface $em, ProductRepository $productRepository, Upload $upload, AllIngredientRepository $allIngredientRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product->setCategorie($form->get('categorie')->getData());
            $product->setName($form->get('name')->getData());
            $product->setPrice($form->get('price')->getData());
            $product->setType($form->get('type')->getData());
            $product->setAvailable($form->get('available')->getData());
            $product->setMeat($form->get('meat')->getData());
            $image = $form->get('image2')->getData();
            $product->setImage($upload->Send($image, $this->getParameter('image_directory')));
			$ordre = $productRepository->findAll();
			$product->setOrdre(count($ordre) + 1);
            $em->persist($product);

            // Ajout  automatique pour certaine categorie
            if($product->getType() === Product::TYPE_COMPOSED ||
                $product->getType() === Product::TYPE_MENU ||
                $product->getType() === Product::TYPE_ONE_MEAT ||
                $product->getType() === Product::TYPE_TWO_MEAT ||
                $product->getType() === Product::TYPE_THREE_MEAT ||
                $product->getType() === Product::TYPE_FOUR_MEAT)
            {
                $ingredients = $allIngredientRepository->findAll();

                foreach($ingredients as $ingredient)
                {
                    if ($ingredient->getType() === AllIngredient::TYPE_SAUCE or $ingredient->getType() === AllIngredient::TYPE_CUISSON or $ingredient->getType() === AllIngredient::TYPE_SUPPLEMENT or $ingredient->getType() === AllIngredient::TYPE_CONDIMENT)
                    {
                        $default = new AllIngredientInProduct();
                        $default->setProduct($product);
                        $default->setIngredient($ingredient);
                        $em->persist($default);
                    }
                }
            }

            $em->flush();

            $this->addFlash('success', "Le produit a été ajouté avec succès");

            return $this->redirectToRoute('Admin_GetAllProducts', ['page' => 1]);
        }

        return $this->render('products/new.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'title' => 'Ajouter un produit'
        ]);
    }

    /**
     * @Route("/Products/{id}", name="Admin_GetProductPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetProductPerId(AllIngredientInProductRepository $allIngredientInProductRepository, AllIngredientRepository $allIngredientRepository, EntityManagerInterface $entityManager, Product $product, ProductRepository $productRepository, Request $request): Response
    {
        $IngredientsInProduct = $allIngredientInProductRepository->findBy(['product' => $product->getId()]);

        $ingredients = [];

        foreach ($IngredientsInProduct as $row)
            array_push($ingredients, $row->getIngredient());

        $ingredient = new AllIngredientInProduct();

        $form = $this->createForm(AllIngredientInProductType::class, null, ['product' => $product]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $find = $allIngredientRepository->find($form->getData()['ingredient']);

            if ($find !== null)
                $ingredient->setIngredient($find);

            if (in_array($ingredient->getIngredient(), $ingredients))
                $this->addFlash('warning', "L'ingrédient est déjà dans la composition");
            else
            {
                $ingredient->setProduct($product);
                $entityManager->persist($ingredient);
                $entityManager->flush();

                $this->addFlash('success', "L'ingrédient a été ajouté dans la composition");
            }

            return $this->redirectToRoute('Admin_GetProductPerId', ['id' => $product->getId()]);
        }

        return $this->render('products/show.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'title' => 'Consulter un produit'
        ]);
    }

    /**
     * @Route("/Products/{id}/Edit", name="Admin_EditProductPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_EditProductPerId(Request $request, Product $product, Upload $upload, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(ProductEditType::class, $product, ['meat' => $product->getMeat()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image2')->getData();
            $product->setMeat($form->get('meat')->getData());

            if ($image instanceof UploadedFile)
            {
                $product->setImage($upload->Send($image, $this->getParameter('image_directory')));
            }

            $em->flush();

            $this->addFlash('success', "Le produit a été édité avec succès");

            return $this->redirectToRoute('Admin_GetAllProducts', ['page' => 1]);
        }

        return $this->render('products/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'title' => 'Editer un produit'
        ]);
    }

    /**
     * @Route("/Products/{id}/Delete", name="Admin_DeleteProductPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DeleteProductPerId($id, EntityManagerInterface $em): Response
    {
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find($id);

        if($product !== null)
        {
            $product->removeCardAll();

            foreach($product->getCards() as $card)
            {
                foreach($card->getCardAllLines() as $allline)
                    $em->remove($allline);
            }

            foreach($product->getAllIngredientInProducts() as $menu)
                $em->remove($menu);

            $em->remove($product);
            $em->flush();

            $this->addFlash('success', "Le produit a été supprimé avec succès");
        }
            return $this->redirectToRoute('Admin_GetAllProducts', ['page' => 1]);

    }

    /**
     * @Route("/Products/{id}/Ascend", name="Admin_AscendProduct")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AscendProduct($id, ProductRepository $productRepository, Product $product, EntityManagerInterface $em): Response
    {
        if ($product->getOrdre() > 1)
        {
            $productFind = $productRepository->findOneBy(['ordre' => $product->getOrdre() - 1, 'type' => $product->getType()]);
            $productFind->setOrdre($product->getOrdre());
            $product->setOrdre($product->getOrdre() - 1);

            $this->addFlash("success", "Le produit a été déplacé vers le haut");

            $em->flush();
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        return $this->redirectToRoute('Admin_GetAllProducts', ['page' => 1]);
    }

    /**
     * @Route("/Products/{id}/Descend", name="Admin_DescendProduct")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DescendProduct($id, ProductRepository $productRepository, Product $product, EntityManagerInterface $em): Response
    {
        $productFind = $productRepository->findOneBy(['ordre' => $product->getOrdre() + 1, 'type' => $product->getType()]);

        if ($productFind !== null)
        {
            $productFind->setOrdre($product->getOrdre());
            $product->setOrdre($product->getOrdre() + 1);

            $this->addFlash("success", "Le produit a bien été déplacé vers le bas");
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        $em->flush();

        return $this->redirectToRoute('Admin_GetAllProducts', ['page' => 1]);
    }

    /**
     * @Route("/Compositions/{id}/Del", name="Admin_compositions_del")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_CompositionsDel(EntityManagerInterface $em, AllIngredientInProduct $ingredientDefault)
    {
        $id = $ingredientDefault->getProduct()->getId();
        $em->remove($ingredientDefault);
        $em->flush();

        $this->addFlash('success', "L'ingrédient a été supprimé de la composition");

        return $this->redirectToRoute('Admin_GetProductPerId', ['id' => $id]);
    }
}
