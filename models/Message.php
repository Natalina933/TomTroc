<?php

/**
 * Entité Message : un message est défini par les champs
 * id, sender_id, receiver_id, content, created_at
 */
class Message extends AbstractEntity
{
    protected int $id;
    private int $senderId;
    private int $receiverId;
    private string $content;
    private int $isRead;
    private DateTime $createdAt;
    private ?User $sender = null;
    private ?User $receiver = null;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $userManager = new UserManager();

        if ($this->senderId) {
            $this->sender = $userManager->getUserById($this->senderId);
        }
        if ($this->receiverId) {
            $this->receiver = $userManager->getUserById($this->receiverId);
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getIsRead(): int
    {
        return $this->isRead;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setIsRead(int $isRead): void
    {
        $this->isRead = $isRead;
    }

    public function setCreatedAt($createdAt): void
    {
        if (is_string($createdAt)) {
            try {
                $this->createdAt = new DateTime($createdAt);
            } catch (Exception $e) {
                throw new InvalidArgumentException("Erreur de conversion de la date : " . $e->getMessage());
            }
        } elseif ($createdAt instanceof DateTime) {
            $this->createdAt = $createdAt;
        } else {
            throw new InvalidArgumentException("L'argument de setCreatedAt doit être une chaîne ou un objet DateTime.");
        }
    }

    // Méthodes supplémentaires
    public function isUnread(): bool
    {
        return $this->isRead === 0;
    }
}
