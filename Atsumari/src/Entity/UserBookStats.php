<?php

namespace App\Entity;

use App\Repository\UserBookStatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBookStatsRepository::class)]
class UserBookStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBookStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'userBookStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book_id = null;

    #[ORM\Column]
    private ?int $total_reading_time = null;

    #[ORM\Column]
    private ?int $sessions_count = null;

    #[ORM\Column]
    private ?int $pages_read_count = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_session_end = null;

    #[ORM\Column]
    private ?float $reading_speed = null;

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

    public function getTotalReadingTime(): ?int
    {
        return $this->total_reading_time;
    }

    public function setTotalReadingTime(int $total_reading_time): static
    {
        $this->total_reading_time = $total_reading_time;

        return $this;
    }

    public function getSessionsCount(): ?int
    {
        return $this->sessions_count;
    }

    public function setSessionsCount(int $sessions_count): static
    {
        $this->sessions_count = $sessions_count;

        return $this;
    }

    public function getPagesReadCount(): ?int
    {
        return $this->pages_read_count;
    }

    public function setPagesReadCount(int $pages_read_count): static
    {
        $this->pages_read_count = $pages_read_count;

        return $this;
    }

    public function getLastSessionEnd(): ?\DateTimeInterface
    {
        return $this->last_session_end;
    }

    public function setLastSessionEnd(\DateTimeInterface $last_session_end): static
    {
        $this->last_session_end = $last_session_end;

        return $this;
    }

    public function getReadingSpeed(): ?float
    {
        return $this->reading_speed;
    }

    public function setReadingSpeed(float $reading_speed): static
    {
        $this->reading_speed = $reading_speed;

        return $this;
    }
}
