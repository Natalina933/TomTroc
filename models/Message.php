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

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
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


    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setCreatedAt($createdAt) : void
    {
        if (is_string($createdAt)) {
            // Si c'est une chaîne, on la convertit en objet DateTime
            try {
                $this->createdAt = new DateTime($createdAt);
            } catch (Exception $e) {
                var_dump("Erreur de conversion de la date : " . $e->getMessage());
            }
        } elseif ($createdAt instanceof DateTime) {
            // Si c'est déjà un objet DateTime, on l'affecte directement
            $this->createdAt = $createdAt;
        } else {
            // Gère le cas où le type n'est ni string ni DateTime
            throw new InvalidArgumentException("L'argument de setCreatedAt doit être une chaîne ou un objet DateTime.");
        }
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
}
