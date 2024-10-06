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
    public function getMessagesByUserId(int $userId): array
    {
        $sql = "
        SELECT 
            message.id AS message_id, 
            message.content, 
            message.createdAt AS message_date, 
            message.isRead,
            user.id AS user_id, 
            user.username, 
            user.email, 
            user.profilePicture
        FROM message
        INNER JOIN user ON message.sender_id = user.id
        WHERE message.receiver_id = :userId
        ORDER BY message.createdAt DESC
    ";

        // Exécution de la requête avec les paramètres
        $stmt = $this->db->query($sql, [':userId' => $userId]);
        $messages = [];

        // Récupération des résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $row; // Ajoutez chaque message au tableau
        }
        // Vérification si aucun message n'a été trouvé
        if (empty($messages)) {
            return []; // Retourne un tableau vide si aucun message n'est trouvé
        }

        return $messages;
    }
    // /**
    //  * Envoie un message.
    //  * @param Message $message : le message à envoyer.
    //  * @return void
    //  */
    public function sendMessage(Message $message): void
    {
        $sql = "INSERT INTO messages (created_at, receiver_id, content ) VALUES (:senderId, :receiverId, :content, NOW())";
        $this->db->query($sql, [
            'created_at' => $message->getSenderId(),
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
