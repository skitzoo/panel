<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/Users/Page/{page}", methods="GET", name="Admin_GetAllUsers", requirements={"page" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $page
     * @return array
     */
    public function Admin_GetAllUsers($page, UserRepository $userRepository): Response
    {
        $usersPerPage = 10;

        $users = $userRepository->getUsers($page, $usersPerPage);

        $pagination = [
            'totalPages' => ceil(count($users) / $usersPerPage),
            'page' => $page
        ];

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'Liste des utilisateurs',
            'users' => $users
        ]);
    }

    /**
     * @Route("/Users/New", name="Admin_AddUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_AddUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => array('User', 'registration'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a été ajouté avec succès");

            return $this->redirectToRoute('Admin_GetAllUsers', ['page' => 1]);
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter un utilisateur',
            'user' => $user
        ]);
    }

    /**
     * @Route("/User/{id}", name="Admin_GetUserPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetUserPerId(User $user)
    {
        return $this->render('user/show.html.twig', [
            'title' => 'Consulter un utilisateur',
            'user' => $user
        ]);
    }

    /**
     * @Route("/User/{id}/Edit", name="Admin_EditUserPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_EditUserPerId(EntityManagerInterface $entityManager, Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data->getPlainPassword() != null)
            {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $entityManager->flush();

            $this->addFlash('success', "L'utiliseur a été édité avec succès");

            return $this->redirectToRoute('Admin_GetAllUsers', ['page' => 1]);
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Editer un utilisateur',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/Users/{id}/Delete", name="Admin_DeleteUserPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_DeleteUserPerId($id, EntityManagerInterface $em, Request $request, User $user): Response
    {
        $usersRepository = $em->getRepository(User::class);
        $user = $usersRepository->find($id);

        if($this->getUser()->getUsername() != $user->getUsername())
        {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été supprimé avec succès");
        }
        else
        {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer votre compte");
        }

        return $this->redirectToRoute('Admin_GetAllUsers', ['page' => 1]);
    }
}
