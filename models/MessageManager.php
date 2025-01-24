<?php

/**
 * Classe qui gère les messages.
 */ class MessageManager extends AbstractEntityManager
{
    private function getMessages(string $whereClause, array $params): array
    {
        $sql = "SELECT * FROM message WHERE $whereClause ORDER BY created_at DESC";
        try {
            $result = $this->db->query($sql, $params);
            return $this->createMessageObjects($result);
        } catch (PDOException $e) {
            $this->logError("Erreur dans la requête SQL : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère tous les messages reçus par un utilisateur.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
    public function getAllMessagesByUserId(int $userId): array
    {
        return $this->getMessages('receiver_id = :userId', ['userId' => $userId]);
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
            $this->logError("Erreur dans la requête SQL : " . $e->getMessage());
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
            $this->logError("Erreur dans la requête SQL : " . $e->getMessage());
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
            $count = (int) $stmt->fetchColumn();

            error_log("User ID : $userId - Messages non lus : $count");

            return $count;
        } catch (PDOException $e) {
            error_log("Erreur dans la requête SQL : " . $e->getMessage());
            return 0;
        }
    }


    public function createNewConversation(int $userId, int $receiverId): void
    {
        // Vérifier si une conversation existe déjà
        $existingConversation = $this->getConversationBetweenUsers($userId, $receiverId);

        if (empty($existingConversation)) {
            // Si aucune conversation n'existe, créer un message initial vide
            $sql = "INSERT INTO message (sender_id, receiver_id, content, created_at) 
                    VALUES (:senderId, :receiverId, '', NOW())";
            try {
                $this->db->query($sql, [
                    'senderId' => $userId,
                    'receiverId' => $receiverId
                ]);
            } catch (PDOException $e) {
                error_log("Erreur lors de la création d'une nouvelle conversation : " . $e->getMessage());
                throw new Exception("Impossible de créer une nouvelle conversation.");
            }
        }
    }

    /**
     * Envoie un message.
     * @param Message $message : le message à envoyer.
     * @return void
     */
    public function sendMessage(Message $message): bool
    {
        $sql = "INSERT INTO message (sender_id, receiver_id, content, created_at) 
                VALUES (:senderId, :receiverId, :content, NOW())";
        try {
            $this->db->query($sql, [
                'senderId' => $message->getSenderId(),
                'receiverId' => $message->getReceiverId(),
                'content' => $message->getContent(),
            ]);
            return true;
        } catch (PDOException $e) {
            $this->logError("Erreur lors de l'envoi du message : " . $e->getMessage());
            return false;
        }
    }


    /**
     * Crée des objets Message à partir des résultats de la requête.
     * @param PDOStatement $result
     * @return array
     */
    private function createMessageObjects($result): array
    {
        $messages = [];
        while ($messageData = $result->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message($messageData);
        }
        return $messages;
    }

    /**
     * Journalise les erreurs.
     * @param string $message
     */
    private function logError(string $message): void
    {
        error_log($message);
    }

    /**
     * Récupère les derniers messages de l'utilisateur $userId.
     * La requête jointe permet de récupérer le dernier message de chaque conversation.
     * @param int $userId
     * @return array : un tableau d'objets Message.
     */
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
     * Met à jour le statut de lecture d'un message.
     * @param int $messageId : l'id du message.
     * @return bool : true si la mise à jour a réussi, false sinon.
     */
    public function markAsRead(int $messageId): bool
    {
        $sql = "UPDATE message SET is_read = 1 WHERE id = :messageId";
        try {
            $this->db->query($sql, ['messageId' => $messageId]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du message : " . $e->getMessage());
            return false;
        }
    }


    /**
     * Marque comme lus tous les messages envoyés par $senderId et reçus par $userId.
     * @param int $userId : l'id de l'utilisateur destinataire.
     * @param int $senderId : l'id de l'utilisateur expéditeur.
     */
    public function markMessagesAsRead(int $userId, int $senderId): void
    {
        $sql = "UPDATE message 
                SET is_read = 1 
                WHERE receiver_id = :userId AND sender_id = :senderId AND is_read = 0";
        try {
            $this->db->query($sql, ['userId' => $userId, 'senderId' => $senderId]);
        } catch (PDOException $e) {
            error_log("Erreur lors du marquage des messages comme lus : " . $e->getMessage());
        }
    }

    /**
     * Renvoie le nombre de messages non lus par conversation pour l'utilisateur donné.
     * @param int $userId : l'id de l'utilisateur.
     * @return array : un tableau contenant le nombre de messages non lus pour chaque conversation.
     *                La clé est l'id de l'expéditeur, la valeur est le nombre de messages non lus.
     */
    public function getUnreadMessagesCountByConversation(int $userId): array
    {
        $sql = "SELECT sender_id, COUNT(*) as unread_count 
                FROM message 
                WHERE receiver_id = :userId AND is_read = 0 
                GROUP BY sender_id";
        try {
            $result = $this->db->query($sql, ['userId' => $userId]);
            $unreadCounts = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $unreadCounts[$row['sender_id']] = $row['unread_count'];
            }
            return $unreadCounts;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du nombre de messages non lus : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime un message par son ID.
     * @param int $id : l'id du message à supprimer.
     * @return void
     */
    public function deleteMessage(int $id): bool
    {
        $sql = "DELETE FROM message WHERE id = :id";
        try {
            $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du message : " . $e->getMessage());
            return false;
        }
    }
}
