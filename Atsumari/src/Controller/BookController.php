<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function collection(): Response
    {
        return $this->render('book/collection.html.twig', [
            'books' => $this->books
        ]);
    }

    #[Route('/books/{id}', name: 'book_details')]
    public function bookDetails($id): Response
    {
        $isCurrentBook = $id == $this->currentBook['id'];
        $existsCurrentBook = $this->currentBook !== null;

        return $this->render('book/details.html.twig', [
            'id' => $id,
            'book' => $this->books[$id],
            'isCurrentBook' => $isCurrentBook,
            'existsCurrentBook' => $existsCurrentBook
        ]);
    }

    #[Route('/current_book', name: 'current_book')]
    public function currentBook(): Response
    {
        return $this->render('book/current_book.html.twig', [
            'book' => $this->currentBook,
            'reading_sessions' => $this->readingSessions
        ]);
    }

    #[Route('/add_book', name: 'add_book')]
    public function addBook(): Response
    {
        return $this->render('book/add_book.html.twig', [
            'text' => 'add book',
        ]);
    }
}
