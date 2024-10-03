<?php

/**
 * Classe qui gère les messages.
 */class MessageManager extends AbstractEntityManager
{
    /**
     * Récupère tous les messages reçus par un utilisateur.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
    public function getAllMessagesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM messages WHERE receiver_id = :userId ORDER BY time_sent DESC";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $messages = [];

        while ($messageData = $result->fetch()) {
            $messages[] = new Message($messageData);
        }
        return $messages;
    }

    /**
     * Récupère tous les messages envoyés par un utilisateur.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
    public function getSentMessages(int $userId): array
    {
        $sql = "SELECT * FROM messages WHERE sender_id = :userId ORDER BY time_sent DESC";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $sentMessages = [];

        while ($messageData = $result->fetch()) {
            $sentMessages[] = new Message($messageData);
        }
        return $sentMessages;
    }

    /**
     * Récupère un message par son ID.
     * @param int $id
     * @return Message|null
     */
    public function getMessageById(int $id): ?Message
    {
        $sql = "SELECT * FROM messages WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        $messageData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $messageData ? new Message($messageData) : null;
    }

    /**
     * Envoie un message.
     * @param Message $message : le message à envoyer.
     * @return void
     */
    public function sendMessage(Message $message): void
    {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, time_sent) VALUES (:senderId, :receiverId, :content, NOW())";
        $this->db->query($sql, [
            'senderId' => $message->getSenderId(),
            'receiverId' => $message->getReceiverId(),
            'content' => $message->getContent(),
        ]);
    }

    /**
     * Supprime un message par son ID.
     * @param int $id : l'id du message à supprimer.
     * @return void
     */
    public function deleteMessage(int $id): void
    {
        $sql = "DELETE FROM messages WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
