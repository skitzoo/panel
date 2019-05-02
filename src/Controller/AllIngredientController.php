<?php

namespace App\Controller;

use App\Entity\AllIngredient;
use App\Form\AllIngredientType;
use App\Repository\AllIngredientRepository;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllIngredientController extends AbstractController
{
    /**
     * @Route("/allingredient", name="all_ingredient_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(AllIngredientRepository $allIngredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', ['all_ingredients' => $allIngredientRepository->getIngredients(), 'title' => 'Ingrédients']);
    }

    /**
     * @Route("/allingredient/new", name="all_ingredient_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Upload $upload, EntityManagerInterface $em): Response
    {
        $allIngredient = new AllIngredient();
        $form = $this->createForm(AllIngredientType::class, $allIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('picture2')->getData();
            $allIngredient->setPicture($upload->Send($image, $this->getParameter('image_directory')));

            $em->persist($allIngredient);
            $em->flush();
			
			$this->addFlash('success', "L'ingrédient a été ajouté avec succès");

            return $this->redirectToRoute('all_ingredient_index');
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $allIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/allingredient/{id}", name="all_ingredient_show")
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(AllIngredient $allIngredient): Response
    {
        return $this->render('ingredient/show.html.twig', ['ingredient' => $allIngredient]);
    }

    /**
     * @Route("/allingredient/{id}/edit", name="all_ingredient_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, AllIngredient $allIngredient, Upload $upload, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AllIngredientType::class, $allIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('picture2')->getData();

            if ($image instanceof UploadedFile)
            {
                $allIngredient->setPicture($upload->Send($image, $this->getParameter('image_directory')));
            }
            $em->flush();

            $this->addFlash('success', "L'ingrédient a été édité avec succès");

            return $this->redirectToRoute('all_ingredient_index');
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $allIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/allingredient/{id}/Ascend", name="all_ingredient_ascend")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AscendIngredient($id, AllIngredientRepository $allIngredientRepository, AllIngredient $allIngredient, EntityManagerInterface $em): Response
    {
        if ($allIngredient->getOrdre() > 1)
        {
            $ingredientFind = $allIngredientRepository->findOneBy(['ordre' => $allIngredient->getOrdre() - 1, 'Type' => $allIngredient->getType()]);
            $ingredientFind->setOrdre($allIngredient->getOrdre());
            $allIngredient->setOrdre($allIngredient->getOrdre() - 1);

            $this->addFlash("success", "L'ingrédient a été déplacé vers le haut");

            $em->flush();
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        return $this->redirectToRoute('all_ingredient_index');
    }

    /**
     * @Route("/allingredient/{id}/Descend", name="all_ingredient_descend")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DescendIngredient($id, AllIngredientRepository $allIngredientRepository, AllIngredient $allIngredient, EntityManagerInterface $em): Response
    {
        $ingredientFind = $allIngredientRepository->findOneBy(['ordre' => $allIngredient->getOrdre() + 1, 'Type' => $allIngredient->getType()]);

        if ($ingredientFind != null)
        {
            $ingredientFind->setOrdre($allIngredient->getOrdre());
            $allIngredient->setOrdre($allIngredient->getOrdre() + 1);

            $this->addFlash("success", "L'ingrédient a bien été déplacé vers le bas");
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue");
        }

        $em->flush();

        return $this->redirectToRoute('all_ingredient_index');
    }

    /**
     * @Route("/allingredient/delete/{id}", name="all_ingredient_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id, EntityManagerInterface $em, AllIngredient $allIngredient)
    {
        foreach ($allIngredient->getAllIngredientInProducts() as $line)
            $em->remove($line);

        foreach ($allIngredient->getCardAllLines() as $line)
            $em->remove($line);

        $em->remove($allIngredient);
        $em->flush();

        $this->addFlash('success', "L'ingrédient a été supprimé avec succès");

        return $this->redirectToRoute('all_ingredient_index');
    }
}
