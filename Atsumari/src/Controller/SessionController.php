<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SessionController extends AbstractController
{
    #[Route('/reading_session', name: 'reading_session')]
    public function readingSession(): Response
    {
        return $this->render('session/reading_session.html.twig', [
            'text' => 'sesja czytania',
        ]);
    }
}
