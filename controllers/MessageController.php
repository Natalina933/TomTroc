<?php
class MessageController
{
    private const ERROR_USER_NOT_FOUND = "Utilisateur non trouvé.";
    private const ERROR_UNAUTHORIZED = "Vous devez être connecté pour effectuer cette action.";
    private const ERROR_INVALID_MESSAGE = "Message ou destinataire invalide.";
    private const ERROR_SENDING_MESSAGE = "Erreur lors de l'envoi du message : ";
    private const ERROR_INVALID_INPUT = "Données d'entrée invalides.";
    private const ERROR_INVALID_STATUS = "Valeur de statut invalide.";
    private $messageManager;

    public function __construct()
    {
        $this->messageManager = new MessageManager();
    }

    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            throw new Exception(self::ERROR_UNAUTHORIZED);
        }
    }

    public function showMessaging(int $receiverId = null): void
    {
        try {
            $this->ensureUserIsConnected();
            $userId = $_SESSION['user']['id'];

            $viewData = $this->getCommonViewData($userId);
            $viewData['conversations'] = $this->messageManager->getLastMessagesByUserId($userId);

            if (isset($_GET['receiver_id'])) {
                $receiverId = (int) $_GET['receiver_id'];
                $receiver = $this->messageManager->getUserById($receiverId);

                if (!$receiver) {
                    throw new Exception(self::ERROR_USER_NOT_FOUND);
                }

                $conversation = $this->messageManager->getConversationBetweenUsers($userId, $receiverId);

                $viewData['activeConversation'] = [
                    'receiver' => $receiver,
                    'messages' => $conversation
                ];

                $this->messageManager->markMessagesAsRead($userId, $receiverId);
            }

            $_SESSION['unreadCount'] = $this->messageManager->getUnreadMessagesCount($userId);

            $view = new View('Messagerie');
            $view->render('messaging', $viewData);
        } catch (Exception $e) {
            Utils::redirect('messaging', ['error' => $e->getMessage()]);
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
            $_SESSION['error'] = self::ERROR_INVALID_MESSAGE;
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
            $_SESSION['error'] = self::ERROR_SENDING_MESSAGE . $e->getMessage();
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
    public function updateMessageStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id']) && isset($data['is_read'])) {
                $messageId = (int)$data['id'];
                $isRead = (bool)$data['is_read'];

                if ($isRead) {
                    $result = $this->messageManager->markAsRead($messageId);

                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result]);
                    exit();
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => self::ERROR_INVALID_STATUS]);
                    exit();
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => self::ERROR_INVALID_INPUT]);
                exit();
            }
        }
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
