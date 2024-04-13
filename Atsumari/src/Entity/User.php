<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserDetails $id_user_details = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserType $user_type_id = null;

    /**
     * @var Collection<int, ReadingSession>
     */
    #[ORM\OneToMany(targetEntity: ReadingSession::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $readingSessions;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'users')]
    private Collection $books;

    /**
     * @var Collection<int, UserBookStats>
     */
    #[ORM\OneToMany(targetEntity: UserBookStats::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $userBookStats;

    public function __construct()
    {
        $this->readingSessions = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->userBookStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getIdUserDetails(): ?UserDetails
    {
        return $this->id_user_details;
    }

    public function setIdUserDetails(UserDetails $id_user_details): static
    {
        $this->id_user_details = $id_user_details;

        return $this;
    }

    public function getUserTypeId(): ?UserType
    {
        return $this->user_type_id;
    }

    public function setUserTypeId(UserType $user_type_id): static
    {
        $this->user_type_id = $user_type_id;

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
            $readingSession->setUserId($this);
        }

        return $this;
    }

    public function removeReadingSession(ReadingSession $readingSession): static
    {
        if ($this->readingSessions->removeElement($readingSession)) {
            // set the owning side to null (unless already changed)
            if ($readingSession->getUserId() === $this) {
                $readingSession->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->books->removeElement($book);

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
            $userBookStat->setUserId($this);
        }

        return $this;
    }

    public function removeUserBookStat(UserBookStats $userBookStat): static
    {
        if ($this->userBookStats->removeElement($userBookStat)) {
            // set the owning side to null (unless already changed)
            if ($userBookStat->getUserId() === $this) {
                $userBookStat->setUserId(null);
            }
        }

        return $this;
    }
}