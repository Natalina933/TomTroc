<?php
class MessageController
{
    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) { // Vérification de la session utilisateur
            Utils::redirect("connectionForm"); // Redirection si l'utilisateur n'est pas connecté
            exit; // On arrête l'exécution pour éviter tout comportement indésirable
        }
    }

    /**
     * Affiche la boîte de réception des messages pour l'utilisateur connecté.
     * @param int|null $receiverId
     * @return void
     */
    public function showMessaging(int $receiverId = null): void
    {
        $this->ensureUserIsConnected(); // Vérifie d'abord si l'utilisateur est connecté
        $userId = $_SESSION['user']['id'];

        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        // Récupérer le nombre de messages non lus
        $unreadCount = $messageManager->getUnreadMessagesCount($userId);

        if (isset($_GET['receiver_id'])) {
            $receiverId = (int) $_GET['receiver_id'];

            // Vérifie que l'utilisateur existe
            $receiver = $messageManager->getUserById($receiverId);
            if (!$receiver) {
                Utils::redirect('messaging?error=Utilisateur non trouvé');
            }

            // Récupère la conversation entre l'utilisateur connecté et l'interlocuteur
            $conversation = $messageManager->getConversationBetweenUsers($userId, $receiverId);

            // Récupère les informations de l'interlocuteur
            $receiverName = $receiver['username'];

            // Rendu de la vue avec le nombre de messages non lus
            $view = new View('Messagerie');
            $view->render('messaging', [
                'messages' => $messageManager->getMessagesByUserId($userId),
                'conversation' => $conversation,
                'receiverId' => $receiverId,
                'receiverName' => $receiverName,
                'unreadCount' => $unreadCount // Ajout du nombre de messages non lus
            ]);
        } else {
            // Récupère tous les messages de l'utilisateur connecté
            $messages = $messageManager->getMessagesByUserId($userId);
            // Rendu de la vue pour la liste des messages
            $view = new View('Messagerie');
            $view->render('messaging', ['messages' => $messages]);
        }
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

    /**
     * Affiche la liste de tous les messages de l'utilisateur.
     * @return void
     */
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
     * Envoie un message.
     * @return void
     */
    public function sendMessage(): void
    {
        $this->ensureUserIsConnected();

        $userId = $_SESSION['user']['id'];
        $receiverId = isset($_POST['receiver_id']) ? (int) $_POST['receiver_id'] : null;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        // Instanciation du MessageManager
        $messageManager = new MessageManager();
        if ($receiverId && !empty($content)) {

            // Création d'un nouveau message
            $message = new Message([
                'sender_id' => $userId,
                'receiver_id' => $receiverId,
                'content' => $content
            ]);

            // Envoi du message
            $messageManager->sendMessage($message);

            // Récupération de la conversation après l'envoi
            $conversation = $messageManager->getConversationBetweenUsers($userId, $receiverId);

            // Rendu de la vue showMessaging avec la conversation
            $view = new View('Messagerie'); // Utilise le bon nom de la vue
            $view->render('messaging', [
                'conversation' => $conversation,
                'receiverId' => $receiverId,
                'successMessage' => 'Message envoyé avec succès!',
                'messages' => $messageManager->getMessagesByUserId($userId), // Pour afficher la liste des messages
                'unreadCount' => $messageManager->getUnreadMessagesCount($userId) // Compte des messages non lus
            ]);
        } else {
            // Redirection ou affichage d'une erreur si le message n'est pas valide
            Utils::redirect('messaging?error=Message ou destinataire invalide');
        }
    }

    /**
     * Affiche la conversation avec un interlocuteur spécifique.
     * @return void
     */
    public function showConversation(): void
    {
        $this->ensureUserIsConnected(); // Vérification de l'utilisateur connecté

        $userId = $_SESSION['user']['id'];
        $receiverId = $_GET['receiver_id'] ?? null; // Récupère l'ID de l'interlocuteur depuis l'URL

        if (!$receiverId) {
            Utils::redirect('messaging'); // Si pas d'ID d'interlocuteur, redirige vers la messagerie
        }

        // Instanciation du MessageManager
        $messageManager = new MessageManager();

        // Récupère la conversation entre l'utilisateur connecté et l'interlocuteur
        $conversation = $messageManager->getConversationBetweenUsers($userId, $receiverId);

        // Rendu de la vue avec la conversation
        $view = new View('Messagerie');
        $view->render('messaging', ['conversation' => $conversation, 'receiverId' => $receiverId]);
    }
}
