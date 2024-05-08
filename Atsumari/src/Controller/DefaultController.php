<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DefaultController extends AbstractController
{
//    private $isAdmin = true;
//    private $users = [
//        [
//            'firstName' => 'Jane',
//            'lastName' => 'Doe',
//            'email' => 'jane.doe@mail.com'
//        ],
//        [
//            'firstName' => 'David',
//            'lastName' => 'Brown',
//            'email' => 'david.brown@mail.com'
//        ],
//        [
//            'firstName' => 'Jane',
//            'lastName' => 'Brown',
//            'email' => 'jane.brown@mail.com'
//        ],
//    ];

    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(): Response
    {
        return $this->render('default/dashboard.html.twig', [
            'bookCover' => null,
            'bookTitle' => null,
        ]);
    }

    #[Route('', name: 'start_page')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
        ]);
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
        return $this->render('default/settings_admin.html.twig', [
//            'users' => $this->users
        ]);
    }
}
