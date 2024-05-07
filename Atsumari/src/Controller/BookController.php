<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class BookController extends AbstractController
{
    private $books;
    private $readingSessions;
    private $currentBook;

    public function __construct()
    {
        $this->books = [
            [
                'id' => 0,
                'title' => 'Book 1',
                'author' => 'Author 1',
                'coverUrl' => 'https://s.znak.com.pl//files/covers/card/f1/T376413.jpg',
                'numOfPages' => 300,
                'genre' => 'fantasy'
            ],
            [
                'id' => 1,
                'title' => 'Book 2',
                'author' => 'Author 2',
                'coverUrl' => 'https://s.znak.com.pl//files/covers/card/fs/T377036.jpg',
                'numOfPages' => 200,
                'genre' => 'fantasy'
            ],
            [
                'id' => 2,
                'title' => 'Book 3',
                'author' => 'Author 3',
                'coverUrl' => 'https://s.znak.com.pl//files/covers/card/f1/T377013.jpg',
                'numOfPages' => 250,
                'genre' => 'fantasy'
            ],
        ];
        $this->readingSessions = [
            ['startDate' => '2024-04-07 11:50', 'endDate' => '2024-04-07 12:00', 'pagesRead' => '10', 'duration' => '600'],
            ['startDate' => '2024-04-07 11:50', 'endDate' => '2024-04-07 12:00', 'pagesRead' => '10', 'duration' => '600'],
            ['startDate' => '2024-04-07 11:50', 'endDate' => '2024-04-07 12:00', 'pagesRead' => '10', 'duration' => '600'],
        ];
        $this->currentBook = $this->books[1];
    }

    #[Route('/books', name: 'collection')]
//    #[IsGranted('ROLE_USER')]
    public function collection(): Response
    {
        return $this->render('book/collection.html.twig', [
            'books' => $this->books
        ]);
    }

    #[Route('/books/{id}', name: 'book_details')]
    #[IsGranted('ROLE_USER')]
    public function bookDetails($id): Response
    {
        $isCurrentBook = $id == $this->currentBook['id'];
        $existsCurrentBook = $this->currentBook !== null;

        return $this->render('book/details.html.twig', [
            'id' => $id,
//            'book' => $this->books[$id],
            'isCurrentBook' => $isCurrentBook,
            'existsCurrentBook' => $existsCurrentBook
        ]);
    }

    #[Route('/current_book', name: 'current_book')]
    #[IsGranted('ROLE_USER')]
    public function currentBook(): Response
    {
        return $this->render('book/current_book.html.twig', [
            'book' => $this->currentBook,
            'reading_sessions' => $this->readingSessions
        ]);
    }

    #[Route('/add_book', name: 'add_book')]
    #[IsGranted('ROLE_USER')]
    public function addBook(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('file');

            dd($file);
            // Obsłuż zapis pliku w katalogu public/uploads
//            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
//            $filename = md5(uniqid()) . '.' . $file->guessExtension();
//            $file->move($uploadsDirectory, $filename);
//
//            // Pobierz inne dane z formularza
//            $title = $request->request->get('title');
//            $author = $request->request->get('author');
//            $pages = $request->request->get('pages');
//            $genre = $request->request->get('genre');

            // Tutaj możesz zapisać dane do bazy danych

            // Przekieruj użytkownika na inną stronę
            return $this->redirectToRoute('collection');
        }
        return $this->render('book/add_book.html.twig', [
            'text' => 'add book',
        ]);
    }
}
