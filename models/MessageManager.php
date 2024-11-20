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
            // var_dump($messageData);
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
     * Récupère les messages reçus par un utilisateur avec les informations des expéditeurs.
     * @param int $userId
     * @return array
     */
    public function getMessagesByUserId(int $userId)
    {
        $sql = "SELECT * FROM message WHERE receiver_id = :id 
        OR sender_id = :id 
        GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id) 
        ORDER BY created_at DESC";
        $stmt = $this->db->query($sql, ['id' => $userId]);
        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message($message);
        }
        return $messages;

        $sql = "
        SELECT 
            message.id AS message_id, 
            message.content, 
            message.created_at AS message_date, 
            message.is_read,
            user.id AS user_id, 
            user.username, 
            user.profilePicture
        FROM message
        INNER JOIN 
        user ON message.sender_id = user.id
        WHERE message.receiver_id = :userId
        GROUP BY message.sender_id
        ORDER BY message.created_at DESC
        ";

        try {
            $stmt = $this->db->query($sql, [':userId' => $userId]);
        } catch (PDOException $e) {
            echo "Erreur dans la requête SQL : " . $e->getMessage();
            return [];
        }
        $messages = [];
        $sqlResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sqlResult;

        while ($row = $sqlResult) {
            $messages[] = [
                'message_id' => $row['message_id'],
                'content' => $row['content'],
                'createdAt' => $row['message_date'],
                'sender' => [
                    'id' => $row['user_id'],
                    'username' => $row['username'],
                    'profilePicture' => $row['profilePicture'] ?? 'assets/images/default-profile.jpg',

                ]
            ]; // on retourne un tableau associatif
        }

        return $messages;
    }

    public function getConversationBetweenUsers(int $userId, int $receiverId): array
    {
        $sql = "
        SELECT 
            *
        FROM 
            message
        WHERE 
        (message.sender_id = :userId 
            AND message.receiver_id = :receiverId)
        OR (message.sender_id = :receiverId AND message.receiver_id = :userId)
        ORDER BY message.created_at ASC";

        $stmt = $this->db->query($sql, [
            ':userId' => $userId,
            ':receiverId' => $receiverId,
        ]);

        $conversation = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conversation[] = new Message($row);
        }

        return $conversation;
    }
    public function createNewConversation(int $userId, int $receiverId)
    {
       $sql = "INSERT INTO message (sender_id, receiver_id, content, created_at)
                VALUES (:sender_id, :receiver_id, :content, NOW())";
        $this->db->query($sql, [
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'content' => ""
        ]);
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
        $stmt = $this->db->query($sql, ['userId' => $userId]);
        return (int) $stmt->fetchColumn();
    }

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