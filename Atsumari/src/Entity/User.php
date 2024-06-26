<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Tests\Fixtures\Metadata\Get;
use App\Controller\UserApiController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Patch(
            denormalizationContext: [
                'groups' => ['user:write:patch']
            ]
        ),
        new Delete(),
        new Delete(
            uriTemplate: '/delete_user',
            controller: UserApiController::class
        )
    ],
    normalizationContext: [
        'groups' => ['user:read'],
    ],
    denormalizationContext: [
        'groups' => ['user:write'],
    ]
)]
#[GetCollection(
    uriTemplate: '/users',
    controller: UserApiController::class,
    normalizationContext: [
        'groups' => ['user:read']
    ]
)]
#[UniqueEntity(fields: ['email'], message: "There is already an account with this email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
//    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write', 'user:write:patch'])]
    private ?string $password = null;

    private ?string $plainPassword;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    #[Groups(['user:read', 'user:write'])]
    private ?UserDetails $id_user_details = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read', 'user:write'])]
    private ?UserType $user_type_id = null;

    /**
     * @var Collection<int, ReadingSession>
     */
    #[ORM\OneToMany(targetEntity: ReadingSession::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $readingSessions;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'users', cascade: ['persist'])]
    #[Groups(['user:read'])]
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
         $this->plainPassword = null;
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
            if ($userBookStat->getUserId() === $this) {
                $userBookStat->setUserId(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
