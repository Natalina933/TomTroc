<?php
class MessageController
{
    /**
     * Affiche la boîte de réception des messages pour l'utilisateur connecté.
     * @return void
     */
    public function showMessaging(): void
    {
        $this->ensureUserIsConnected(); // Vérifie d'abord si l'utilisateur est connecté
        // var_dump($_SESSION);
        $userId = $_SESSION['user']['id'];

        // Instanciation du messageManager
        $messageManager = new MessageManager();

        // Récupère tous les messages de l'utilisateur connecté
        $messages = $messageManager->getAllMessagesByUserId($userId);
        // var_dump($messages);
        // Rendu de la vue pour la messagerie
        $view = new View('Messagerie');
        $view->render('messaging', ['messages' => $messages]);
    }

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
    // public function sendMessage(): void
    // {
    //     // Vérifie si les champs content et receiverId sont définis dans le formulaire
    //     if (isset($_POST['content']) && isset($_POST['receiverId'])) {
    //         $content = $_POST['content'];
    //         $receiverId = $_POST['receiverId'];
    //         $senderId = $_SESSION['user']['id'];

    //         // Instanciation du messageManager
    //         $messageManager = new MessageManager();

    //         // Crée un nouvel objet Message en passant les données sous forme de tableau
    //         $message = new Message([
    //             'sender_id' => $senderId,
    //             'receiver_id' => $receiverId,
    //             'content' => $content
    //         ]);

    //         // Envoie le message
    //         $messageManager->sendMessage($message);

    //         // Redirige ou affiche un message de succès
    //         Utils::redirect("messagerie");
    //     } else {
    //         // Affiche un message d'erreur si les données requises ne sont pas présentes
    //         echo "Les champs 'content' et 'receiverId' sont requis.";
    //     }
    // }
}
