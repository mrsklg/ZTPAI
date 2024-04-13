<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('security/login.html.twig');
    }

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('login');
        }
        return $this->render('security/signup.html.twig');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('start_page');
    }
}
