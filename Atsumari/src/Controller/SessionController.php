<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\UserBookStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SessionController extends AbstractController
{
    #[Route('/reading_session', name: 'reading_session')]
    #[isGranted('ROLE_USER')]
    public function readingSession(BookRepository $bookRepository, UserBookStatsRepository $bookStatsRepository): Response
    {
        return $this->render('session/reading_session.html.twig');
    }
}
