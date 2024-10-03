<?php
class MessageController
{
    /**
     * Affiche la boîte de réception des messages pour l'utilisateur connecté.
     * @return void
     */
    public function showInbox(): void
    {
        //Instanciation du messageManager
        $messageManager = new MessageManager();
        // Récupère tous les messages de l'utilisateur connecté
        $userId = $_SESSION['user']['id'];
        $messages = $messageManager->getAllMessagesByUserId($userId);
        $view = new View('Messagerie');
        $view->render('inbox', ['messages' => $messages]);
    }

    /**
     * Affiche les messages envoyés par l'utilisateur.
     * @return void
     */
    public function showSentMessages(): void
    {
        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        // Récupérer les messages envoyés de l'utilisateur connecté
        $userId = $_SESSION['user']['id'];
        $sentMessages = $messageManager->getSentMessages($userId);
        $view = new View('Messagerie');
        $view->render('sentMessages', ['messages' => $sentMessages]);
    }
    public function showMessagesList(): void
    {
        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        // Récupère l'ID de l'utilisateur connecté
        $userId = $_SESSION['user']['id'];
        // Récupère tous les messages de l'utilisateur connecté (boîte de réception et messages envoyés)
        $messages = $messageManager->getAllMessagesByUserId($userId);

        // Instancie la vue et affiche la liste des messages
        $view = new View('Messagerie');
        $view->render('messagesList', ['messages' => $messages]);
    }
    /**
     * Envoie un nouveau message après validation des données.
     * @return void
     */
    public function sendMessage(): void
    {
        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        // Récupérer les données du formulaire
        $senderId = $_SESSION['user']['id'];
        $receiverId = Utils::request('receiverId');
        $content = Utils::request('content');

        // Validation des données
        if (empty($receiverId) || empty($content)) {
            // Gérer l'erreur si les champs sont vides
            Utils::redirect('showNewMessageForm', ['error' => 'Les champs sont obligatoires']);
            return;
        }

        // Création du message
        $message = new Message();
        $message->setSenderId($senderId);
        $message->setReceiverId((int)$receiverId);
        $message->setContent($content);
        $message->setTimeSent(date('Y-m-d H:i:s'));

        // Envoi du message
        $messageManager->sendMessage($message);

        // Redirection vers la page des messages envoyés
        Utils::redirect('showSentMessages');
    }

    /**
     * Supprime un message de la boîte de réception.
     * @param int $messageId
     * @return void
     */
    public function deleteMessage(int $messageId): void
    {
        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        // Suppression du message
        $messageManager->deleteMessage($messageId);
        // Redirection vers la boîte de réception
        Utils::redirect('showInbox');
    }
}
