<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\ReadingSessionRepository;
use App\Repository\UserBookStatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
class BookController extends AbstractController
{
    #[Route('/books', name: 'collection')]
    #[IsGranted('ROLE_USER')]
    public function collection(): Response
    {
        return $this->render('book/collection.html.twig');
    }

    #[Route('/books/{id}', name: 'book_details')]
    #[IsGranted('ROLE_USER')]
    public function bookDetails($id, BookRepository $bookRepository): Response
    {
        return $this->render('book/details.html.twig');
    }

    #[Route('/current_book', name: 'current_book')]
    #[IsGranted('ROLE_USER')]
    public function currentBook(BookRepository $bookRepository, ReadingSessionRepository $sessionRepository, UserBookStatsRepository $bookStatsRepository): Response
    {
        $userId = $this->getUser()->getId();
        $currentBook = $bookRepository->getCurrentBookForUser($userId);
        if ($currentBook) {
            $bookId = $currentBook['id'];
        } else {
            $bookId = null;
        }

        $userBookStats = $bookStatsRepository->findOneBy(['user_id' => $userId, 'book_id' => $bookId]);
        return $this->render('book/current_book.html.twig', [
            'book' => $currentBook,
            'stats' => $userBookStats
        ]);
    }

    #[Route('/add_book', name: 'add_book')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        $user = $em->getRepository(User::class)->findOneBy(['id' => $this->getUser()->getId()]);
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        if ($request->isMethod('POST')) {
            $bookTitle = $request->request->all()["book"]["title"];
            $existingBook = $em->getRepository(Book::class)->findOneBy(['title' => $bookTitle]);

            if ($existingBook) {
                if (!$user->getBooks()->contains($existingBook)) {
                    $user->addBook($existingBook);
                    $existingBook->addUser($user);
                    $em->flush();

                    return $this->redirectToRoute('collection');
                } else {
                    return $this->redirectToRoute('collection', ['error' => 'Książka już znajduje się w Twojej kolekcji.']);
                }
            } else {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $coverFile = $form->get('coverFile')->getData();

                    if ($coverFile) {
                        $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                        try {
                            $coverFile->move(
                                $this->getParameter('uploads_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                        }

                        $book->setCoverUrl('/uploads/' . $newFilename);
                    }

                    foreach ($book->getAuthors() as $author) {
                        $existingAuthor = $em->getRepository(Author::class)->findOneBy([
                            'first_name' => $author->getFirstName(),
                            'last_name' => $author->getLastName()
                        ]);

                        if ($existingAuthor) {
                            $book->removeAuthor($author);
                            $book->addAuthor($existingAuthor);
                        } else {
                            $em->persist($author);
                        }
                    }

                    foreach ($book->getGenres() as $genre) {
                        $existingGenre = $em->getRepository(Genre::class)->findOneBy([
                            'genre_name' => $genre->getGenreName()
                        ]);

                        if ($existingGenre) {
                            $book->removeGenre($genre);
                            $book->addGenre($existingGenre);
                        } else {
                            $em->persist($genre);
                        }
                    }

                    $book->addUser($user);
                    $user->addBook($book);

                    $em->persist($book);
                    $em->flush();

                    return $this->redirectToRoute('collection');
                }
            }
        }

        return $this->render('book/add_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
