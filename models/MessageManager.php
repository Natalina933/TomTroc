<?php

/**
 * Classe qui gère les messages.
 */
class MessageManager extends AbstractEntityManager
{
    /**
     * Récupère tous les messages d'un utilisateur.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
    public function getAllMessagesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM messages WHERE receiver_id = :userId ORDER BY time_sent DESC";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $messages = [];

        while ($message = $result->fetch()) {
            $messages[] = new Message($message);
        }
        return $messages;
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

    /**
     * Compte le nombre de messages reçus par un utilisateur.
     * @param int $userId
     * @return int
     */
    public function countUserMessages(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total_messages FROM messages WHERE receiver_id = :userId";
        $stmt = $this->db->query($sql, ['userId' => $userId]);
        $result = $stmt->fetch();

        return (int)$result['total_messages'];
    }
}
