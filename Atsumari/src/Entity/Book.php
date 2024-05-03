<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\BookApiController;
use App\Controller\BookController;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: [
        'groups' => ['book:read']
    ],
    denormalizationContext: [
        'groups' => ['book:write']
    ]
)]
#[Post(
    uriTemplate: '/add_book_to_db',
    controller: BookApiController::class,
    denormalizationContext: [
        'groups' => ['book:write']
    ])]
#[Delete(
    uriTemplate: '/api/remove_book/{book_id}/user/{user_id}',
    uriVariables: [
        'book_id' => new Link(
            fromClass: Book::class
        ),
        'user_id' => new Link(
            fromProperty: 'books',
            fromClass: User::class
        )
    ],
    controller: BookApiController::class
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/books',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'books',
            fromClass: User::class
        )
    ],
    normalizationContext: [
        'groups' => 'user:read'
    ]
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/books/{book_id}',
    operations: [new Get()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'books',
            fromClass: User::class
        ),
        'book_id' => new Link(
            fromClass: Book::class
        )
    ],
    normalizationContext: [
        'groups' => 'user:read'
    ]
)]
#[UniqueEntity(fields: ['title'], message: "There is already a book with this title")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:write', 'book:read', 'user:read'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['book:write', 'book:read', 'user:read'])]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $num_of_pages = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book:write', 'book:read', 'user:read'])]
    private ?string $cover_url = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'books', cascade: ['persist'])]
    #[Groups(['book:write', 'book:read', 'user:read'])]
    private Collection $genres;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books', cascade: ['persist'])]
    #[Groups(['book:write', 'book:read', 'user:read'])]
    #[Assert\Valid]
    private Collection $authors;

    /**
     * @var Collection<int, ReadingSession>
     */
    #[ORM\OneToMany(targetEntity: ReadingSession::class, mappedBy: 'book_id', orphanRemoval: true)]
    private Collection $readingSessions;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'books', cascade: ['persist'])]
    #[Groups(['book:read'])]
    private Collection $users;

    /**
     * @var Collection<int, UserBookStats>
     */
    #[ORM\OneToMany(targetEntity: UserBookStats::class, mappedBy: 'book_id', orphanRemoval: true)]
    private Collection $userBookStats;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->readingSessions = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userBookStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getNumOfPages(): ?int
    {
        return $this->num_of_pages;
    }

    public function setNumOfPages(int $num_of_pages): static
    {
        $this->num_of_pages = $num_of_pages;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->cover_url;
    }

    public function setCoverUrl(?string $cover_url): static
    {
        $this->cover_url = $cover_url;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, ReadingSession>
     */
    public function getReadingSessions(): Collection
    {
        return $this->readingSessions;
    }

    public function addReadingSession(ReadingSession $readingSession): static
    {
        if (!$this->readingSessions->contains($readingSession)) {
            $this->readingSessions->add($readingSession);
            $readingSession->setBookId($this);
        }

        return $this;
    }

    public function removeReadingSession(ReadingSession $readingSession): static
    {
        if ($this->readingSessions->removeElement($readingSession)) {
            // set the owning side to null (unless already changed)
            if ($readingSession->getBookId() === $this) {
                $readingSession->setBookId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addBook($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBookStats>
     */
    public function getUserBookStats(): Collection
    {
        return $this->userBookStats;
    }

    public function addUserBookStat(UserBookStats $userBookStat): static
    {
        if (!$this->userBookStats->contains($userBookStat)) {
            $this->userBookStats->add($userBookStat);
            $userBookStat->setBookId($this);
        }

        return $this;
    }

    public function removeUserBookStat(UserBookStats $userBookStat): static
    {
        if ($this->userBookStats->removeElement($userBookStat)) {
            // set the owning side to null (unless already changed)
            if ($userBookStat->getBookId() === $this) {
                $userBookStat->setBookId(null);
            }
        }

        return $this;
    }
}
