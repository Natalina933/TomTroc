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
    private int $createdAt;

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getCreatedAt(): int
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
