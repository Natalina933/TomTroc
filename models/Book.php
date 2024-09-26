<?php

/**
 * Entité Book, un livre est défini par les champs
 * id, user_id, title, author, image, description, available, created_at, updated_at
 */
class Book extends AbstractEntity
{
    protected int $id;
    private int $userId;
    private string $title = "";
    private string $author = "";
    private ?string $img = null;
    private string $description = "";
    private bool $available = true;
    private string $numberOfBook;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    // Getter et Setter pour id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    // Setter et Getter pour userId
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    // Setter et Getter pour title
    public function setTitle(string $title): void
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Le titre ne peut pas être vide.');
        }
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    // Setter et Getter pour author
    public function setAuthor(string $author): void
    {
        if (empty($author)) {
            throw new InvalidArgumentException('L\'auteur ne peut pas être vide.');
        }
        $this->author = $author;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    // Setter et Getter pour image
    public function setImg(?string $img): void
    {
        $this->img = $img;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    // Setter et Getter pour description
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(int $length = -1): string
    {
        if ($length > 0) {
            $description = mb_substr($this->description, 0, $length);
            if (mb_strlen($this->description) > $length) {
                $description .= "...";
            }
            return $description;
        }
        return $this->description;
    }

    // Setter et Getter pour available
    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function getNumberOfBook(): string
    {
        return $this->numberOfBook;
    }
    public function setNumberOfBook(string $numberOfBook): void
    {
        $this->numberOfBook = $numberOfBook;
    }

    // Setter et Getter pour createdAt
    public function setCreatedAt(string|DateTime $createdAt, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($createdAt)) {
            $createdAt = DateTime::createFromFormat($format, $createdAt);
            if (!$createdAt) {
                throw new InvalidArgumentException('Format de date invalide pour createdAt.');
            }
        }
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    // Setter et Getter pour updatedAt
    public function setUpdatedAt(string|DateTime|null $updatedAt): void
    {
        if ($updatedAt === null) {
            $this->updatedAt = null;
        } elseif (is_string($updatedAt)) {
            $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $updatedAt);
            if (!$this->updatedAt) {
                throw new InvalidArgumentException('Format de date invalide pour updatedAt.');
            }
        } elseif ($updatedAt instanceof DateTime) {
            $this->updatedAt = $updatedAt;
        }
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}
