<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    private $isAdmin = true;
    private $users = [
        [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'jane.doe@mail.com'
        ],
        [
            'firstName' => 'David',
            'lastName' => 'Brown',
            'email' => 'david.brown@mail.com'
        ],
        [
            'firstName' => 'Jane',
            'lastName' => 'Brown',
            'email' => 'jane.brown@mail.com'
        ],
    ];

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('default/dashboard.html.twig', [
            'bookCover' => null,
            'bookTitle' => null
        ]);
    }

    #[Route('', name: 'start_page')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
        ]);
    }

    #[Route('/stats', name: 'stats')]
    public function stats(): Response
    {
        return $this->render('default/stats.html.twig', [
        ]);
    }

    #[Route('/settings', name: 'settings')]
    #[Route('/settings_admin', name: 'settings_admin')]
    public function settings(): Response
    {
        if($this->isAdmin) {
            return $this->render('default/settings_admin.html.twig', [
                'users' => $this->users
            ]);
        }
        return $this->render('default/settings.html.twig', [
        ]);
    }
}
