<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [
            'text' => 'logowanie',
        ]);
    }

    #[Route('/signup', name: 'signup')]
    public function signup(): Response
    {
        return $this->render('security/signup.html.twig', [
            'text' => 'rejestracja',
        ]);
    }
}
