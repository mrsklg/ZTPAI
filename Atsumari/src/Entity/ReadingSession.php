<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\BookApiController;
use App\Controller\SessionApiController;
use App\Repository\ReadingSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReadingSessionRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/reading_sessions',
            controller: SessionApiController::class
        ),
        new Post(
            uriTemplate: '/reading_session',
            controller: SessionApiController::class
        )
    ],
    normalizationContext: [
        'groups' => ['readingSession:read']
    ],
     denormalizationContext: [
        'groups' => ['readingSession:write']
]
)]
class ReadingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'readingSessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['readingSession:read'])]//, 'readingSession:write'])]
    #[Assert\NotBlank]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'readingSessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['readingSession:read'])]//, 'readingSession:write'])]
    #[Assert\NotBlank]
    private ?Book $book_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['readingSession:read', 'readingSession:write'])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    #[Groups(['readingSession:read', 'readingSession:write'])]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $pages_read = null;

    #[ORM\Column]
    #[Groups(['readingSession:read', 'readingSession:write'])]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(60)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['readingSession:read', 'readingSession:write'])]
    private ?\DateTimeInterface $start_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookId(): ?Book
    {
        return $this->book_id;
    }

    public function setBookId(?Book $book_id): static
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getPagesRead(): ?int
    {
        return $this->pages_read;
    }

    public function setPagesRead(int $pages_read): static
    {
        $this->pages_read = $pages_read;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }
}
