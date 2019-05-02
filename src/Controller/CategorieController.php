<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieEditType;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{

    /**
     * @Route("/Categories/Page/{page}", name="Admin_GetAllCategories", requirements={"page" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $page
     * @return array
     */
    public function Admin_getCategories($page, CategorieRepository $categorieRepository): Response
    {
        $categoriesPerPage = 25;

        $categories = $categorieRepository->getCategoriesAdmin($page, $categoriesPerPage);

        $pagination = [
            'totalPages' => ceil(count($categories) / $categoriesPerPage),
            'page' => $page
        ];

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
            'title' => 'Liste des catégories',
        ]);
    }

    /**
     * @Route("/Categories/New", name="Admin_AddCategory")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AddCategory(Request $request, CategorieRepository $categorieRepository, Upload $upload, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image2')->getData();
            $categorie->setImage($upload->Send($image, $this->getParameter('image_directory')));

            $ordre = $categorieRepository->findAll();
            $categorie->setOrdre(count($ordre) + 1);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie a été ajoutée avec succès");

            return $this->redirectToRoute('Admin_GetAllCategories', ['page' => 1]);
        }

        return $this->render('categories/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Ajouter une catégorie'
        ]);
    }

    /**
     * @Route("/Categories/{id}", name="Admin_GetCategoryPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetCategoryPerId(Categorie $categorie): Response
    {
        return $this->render('categories/show.html.twig', [
            'category' => $categorie,
            'title' => 'Consulter une catégorie'
        ]);
    }

    /**
     * @Route("/Categories/{id}/Edit", name="Admin_EditCategoryPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_EditCategoryPerId(Request $request, Categorie $categorie, Upload $upload, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieEditType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image2')->getData();

            if ($image instanceof UploadedFile)
            {
                $categorie->setImage($upload->Send($image, $this->getParameter('image_directory')));
            }
            $em->flush();

            $this->addFlash('success', "La catégorie a été éditée avec succès");

            return $this->redirectToRoute('Admin_GetAllCategories', ['page' => 1]);
        }

        return $this->render('categories/edit.html.twig', [
            'category' => $categorie,
            'form' => $form->createView(),
            'title' => 'Editer une catégorie'
        ]);
    }

    /**
     * @Route("/Categories/{id}/Ascend", name="Admin_AscendCategory")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AscendCategory($id, CategorieRepository $categorieRepository, Categorie $categorie, EntityManagerInterface $em): Response
    {
        if ($categorie->getOrdre() > 1)
        {
            $categorieFind = $categorieRepository->findOneBy(['ordre' => $categorie->getOrdre() - 1]);
            $categorieFind->setOrdre($categorie->getOrdre());
            $categorie->setOrdre($categorie->getOrdre() - 1);

            $this->addFlash("success", "La catégorie a été déplacée vers le haut");

            $em->flush();
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        return $this->redirectToRoute('Admin_GetAllCategories', ['page' => 1]);
    }

    /**
     * @Route("/Categories/{id}/Descend", name="Admin_DescendCategory")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DescendCategory($id, CategorieRepository $categorieRepository, Categorie $categorie, EntityManagerInterface $em): Response
    {
        $categorieFind = $categorieRepository->findOneBy(['ordre' => $categorie->getOrdre() + 1]);

        if ($categorieFind !== null)
        {
            $categorieFind->setOrdre($categorie->getOrdre());
            $categorie->setOrdre($categorie->getOrdre() + 1);

            $this->addFlash("success", "La catégorie a bien été déplacée vers le bas");
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        $em->flush();

        return $this->redirectToRoute('Admin_GetAllCategories', ['page' => 1]);
    }

    /**
     * @Route("/Categories/{id}/Delete", name="Admin_DeleteCategoriePerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DeleteCategoriePerId($id, Categorie $categorie, EntityManagerInterface $em): Response
    {
        if($categorie !== null)
        {
            $em->remove($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie a été supprimée avec succès");
        }
        return $this->redirectToRoute('Admin_GetAllCategories', ['page' => 1]);
    }
}
