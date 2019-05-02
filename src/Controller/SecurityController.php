<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function logout(Request $request)
    {
        $security = $this->container->get('security.authorization_checker');

        if($security->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            if($this->get('security.token_storage')->getToken()->getUser())
            {
                $this->get('security.token_storage')->setToken(null);
            }

            $cookie = $request->cookies;

            if($cookie->has('REMEMBERME'))
            {
                $response = new Response();
                $response->headers->clearCookie('REMEMBERME');
                $response->sendHeaders();
            }

            $this->addFlash('logout', 'Logout');

        }
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/Admin/Login", name="login")
     *
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'last_username' => $lastUsername,
            'title' => 'Connexion',
        ]);
    }
}
