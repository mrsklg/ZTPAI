<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DefaultController extends AbstractController {
    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(): Response
    {
        return $this->render('default/dashboard.html.twig');
    }

    #[Route('', name: 'start_page')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/stats', name: 'stats')]
    #[IsGranted('ROLE_USER')]
    public function stats(): Response
    {
        return $this->render('default/stats.html.twig');
    }

    #[Route('/settings', name: 'settings')]
    #[IsGranted('ROLE_USER')]
    public function settings(): Response
    {
        return $this->render('default/settings.html.twig');
    }

    #[Route('/settings_admin', name: 'settings_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function settingsAdmin(): Response
    {
        return $this->render('default/settings_admin.html.twig');
    }
}
