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
     * Rend une vue.
     * @param string $viewName
     * @param string $pageTitle
     * @param array $data
     * @return void
     */
    private function renderView(string $viewName, string $pageTitle, array $data = []): void
    {
        $view = new View($pageTitle);
        $view->render($viewName, $data);
    }

    /**
     * Vérifie que l'utilisateur a un rôle spécifique.
     * @param string $role
     * @throws Exception
     */
    private function ensureUserHasRole(string $role): void
    {
        if (isset($_SESSION['user']) || $_SESSION['user']->getRole() !== $role) {
            throw new Exception("Vous n'avez pas les droits nécessaires pour accéder à cette page.");
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
}
