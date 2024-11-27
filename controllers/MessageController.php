<?php
class MessageController
{
    private $messageManager;

    public function __construct()
    {
        $this->messageManager = new MessageManager();
    }

    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
            exit;
        }
    }

    public function showMessaging(int $receiverId = null): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];

        $viewData = $this->getCommonViewData($userId);

        if (isset($_GET['receiver_id'])) {
            $receiverId = (int) $_GET['receiver_id'];
            $receiver = $this->messageManager->getUserById($receiverId);

            if (!$receiver) {
                Utils::redirect('messaging?error=Utilisateur non trouvé');
                exit;
            }

            // Vérifie si une conversation existe déjà entre l'utilisateur et le destinataire
            $conversation = $messageManager->getConversationBetweenUsers($userId, $receiverId);

            // Si aucune conversation n'existe, créer un message initial pour démarrer la conversation
            if (empty($conversation)) {
                // $messageManager->createNewConversation($userId, $receiverId);
                // Recharger la conversation pour inclure le message initial
                $conversation = $messageManager->getConversationBetweenUsers($userId, $receiverId);
            }
            $view = new View('Messagerie');
            $view->render('messaging', [
                'messages' => $conversation,
                'receiverName' => htmlspecialchars($receiver['username'], ENT_QUOTES, 'UTF-8'),
                'unreadCount' => $unreadCount
            ]);
        } else {
            // Afficher tous les messages si aucun `receiver_id` n'est passé
            $messages = $messageManager->getMessagesByUserId($userId);
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
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];
        $sentMessages = $this->messageManager->getSentMessages($userId);
        $this->renderView('sentMessages', ['messages' => $sentMessages]);
    }

    public function showMessagesList(): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];
        $messages = $this->messageManager->getAllMessagesByUserId($userId);
        $this->renderView('messagesList', ['messages' => $messages]);
    }

    public function sendMessage(): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];

        $receiverId = filter_input(INPUT_POST, 'receiver_id', FILTER_VALIDATE_INT);
        $content = htmlspecialchars(trim($_POST['content']), ENT_QUOTES, 'UTF-8');

        if (!$receiverId || empty($content)) {
            $_SESSION['error'] = 'Message ou destinataire invalide.';
            Utils::redirect('messaging');
            exit;
        }

        try {
            $message = new Message([
                'sender_id' => $userId,
                'receiver_id' => $receiverId,
                'content' => $content
            ]);
            $this->messageManager->sendMessage($message);

            $viewData = $this->getCommonViewData($userId);
            $viewData['conversation'] = $this->messageManager->getConversationBetweenUsers($userId, $receiverId);
            $viewData['receiverId'] = $receiverId;
            $viewData['successMessage'] = 'Message envoyé avec succès!';

            $this->renderView('messaging', $viewData);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors de l\'envoi du message : ' . $e->getMessage();
            Utils::redirect('messaging');
        }
    }

    public function showConversation(): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];
        $receiverId = $_GET['receiver_id'] ?? null;

        if (!$receiverId) {
            Utils::redirect('messaging');
        }

        $viewData = $this->getCommonViewData($userId);
        $viewData['conversation'] = $this->messageManager->getConversationBetweenUsers($userId, $receiverId);
        $viewData['receiverId'] = $receiverId;

        $this->renderView('messaging', $viewData);
    }

    private function getCommonViewData(int $userId): array
    {
        return [
            'messages' => $this->messageManager->getMessagesByUserId($userId),
            'unreadCount' => $this->messageManager->getUnreadMessagesCount($userId),
            'lastMessages' => $this->messageManager->getLastMessagesByUserId($userId)
        ];
    }

    private function renderView(string $viewName, array $data): void
    {
        $view = new View('Messagerie');
        $view->render($viewName, $data);
    }
}
