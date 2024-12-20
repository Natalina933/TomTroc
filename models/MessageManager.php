<?php

/**
 * Classe qui gère les messages.
 */ class MessageManager extends AbstractEntityManager
{
    /**
     * Récupère tous les messages reçus par un utilisateur.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
    public function getAllMessagesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM message WHERE receiver_id = :userId ORDER BY created_at DESC";
        try {
            $result = $this->db->query($sql, ['userId' => $userId]);
        } catch (PDOException $e) {
            echo "Erreur dans la requête SQL : " . $e->getMessage();
        }
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
        $sql = "SELECT * FROM message WHERE sender_id = :userId ORDER BY created_at DESC";
        try {
            $result = $this->db->query($sql, ['userId' => $userId]);
            $sentMessages = [];
            while ($messageData = $result->fetch(PDO::FETCH_ASSOC)) {
                $sentMessages[] = new Message($messageData);
            }
            return $sentMessages;
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return [];
        }
    }
    /**
     * Récupère un message par son ID.
     * @param int $id
     * @return Message|null
     */
    public function getMessageById(int $id): ?Message
    {
        $sql = "SELECT * FROM message WHERE id = :id";
        try {
            $stmt = $this->db->query($sql, ['id' => $id]);
            $messageData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $messageData ? new Message($messageData) : null;
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return null;
        }
    }
    /**
     * Récupère les messages reçus par un utilisateur avec les informations des expéditeurs.
     * @param int $userId
     * @return array
     */
    public function getMessagesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM message 
                WHERE receiver_id = :id OR sender_id = :id 
                GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id) 
                ORDER BY created_at DESC";
        try {
            $stmt = $this->db->query($sql, ['id' => $userId]);
            $messages = [];
            while ($message = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $messages[] = new Message($message);
            }
            return $messages;
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return [];
        }
    }

    public function getConversationBetweenUsers(int $userId, int $receiverId): array
    {
        $sql = "SELECT * FROM message
                WHERE (sender_id = :userId AND receiver_id = :receiverId)
                   OR (sender_id = :receiverId AND receiver_id = :userId)
                ORDER BY created_at ASC";
        try {
            $stmt = $this->db->query($sql, [
                'userId' => $userId,
                'receiverId' => $receiverId,
            ]);
            $conversation = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $conversation[] = new Message($row);
            }
            return $conversation;
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return [];
        }
    }


    /**
     * Récupère un utilisateur par son ID.
     * @param int $id
     * @return array|null
     */
    public function getUserById(int $id): ?array
    {
        $sql = "SELECT id, username, profilePicture FROM user WHERE id = :id";
        try {
            $stmt = $this->db->query($sql, ['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user : null;
        } catch (PDOException $e) {
            echo "Erreur dans la requête SQL : " . $e->getMessage();
            return null;
        }
    }
    public function getUnreadMessagesCount(int $userId): int
    {
        $sql = "SELECT COUNT(*) FROM message WHERE receiver_id = :userId AND is_read = 0";
        try {
            $stmt = $this->db->query($sql, ['userId' => $userId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return 0;
        }
    }
    // public function markMessagesAsRead(int $userId, int $senderId): void
    // {
    //     $sql = "UPDATE message SET is_read = 1 WHERE receiver_id = :userId AND sender_id = :senderId AND is_read = 0";
    //     try {
    //         $this->db->query($sql, ['userId' => $userId, 'senderId' => $senderId]);
    //     } catch (PDOException $e) {
    //         error_log("Erreur lors du marquage des messages comme lus : " . $e->getMessage());
    //     }
    // }

    /**
     * Envoie un message.
     * @param Message $message : le message à envoyer.
     * @return void
     */
    public function sendMessage(Message $message): void
    {
        $sql = "INSERT INTO message (sender_id, receiver_id, content, created_at) VALUES (:senderId, :receiverId, :content, NOW())";
        try {
            $this->db->query($sql, [
                'senderId' => $message->getSenderId(),
                'receiverId' => $message->getReceiverId(),
                'content' => $message->getContent(),
            ]);
        } catch (PDOException $e) {
            echo "Erreur lors de l'envoi du message : " . $e->getMessage();
        }
    }
    public function getLastMessagesByUserId(int $userId): array
    {
        $sql = "SELECT m.* FROM message m
            INNER JOIN (
                SELECT LEAST(sender_id, receiver_id) as user1, GREATEST(sender_id, receiver_id) as user2, MAX(created_at) as max_date
                FROM message
                WHERE sender_id = :userId OR receiver_id = :userId
                GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
            ) latest ON (m.sender_id = latest.user1 AND m.receiver_id = latest.user2) OR (m.sender_id = latest.user2 AND m.receiver_id = latest.user1)
            WHERE m.created_at = latest.max_date
            ORDER BY m.created_at DESC";

        $stmt = $this->db->query($sql, ['userId' => $userId]);
        $messages = [];
        while ($messageData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message($messageData);
        }
        return $messages;
    }

    /**
     * Supprime un message par son ID.
     * @param int $id : l'id du message à supprimer.
     * @return void
     */
    public function deleteMessage(int $id): void
    {
        $sql = "DELETE FROM message WHERE id = :id";
        try {
            $this->db->query($sql, ['id' => $id]);
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du message : " . $e->getMessage();
        }
    }
}
