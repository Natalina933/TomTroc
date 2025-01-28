<?php
class MessageController
{
    private $messageManager;

    public function __construct()
    {
        $this->messageManager = new MessageManager();
    }

    /**
     * Vérifie si l'utilisateur est connecté et le redirige vers la page de connexion si ce n'est pas le cas.
     * @return void
     * @throws Exception si une erreur survient
     */
    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
            exit;
        }
    }

    /**
     * Affiche la page de messagerie.
     * Si l'ID d'un destinataire est fourni, charge la conversation correspondante.
     * Marque les messages de la conversation comme lus.
     * @param int $receiverId ID du destinataire
     * @return void
     * @throws Exception si une erreur survient
     */
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
                    throw new Exception("Utilisateur non trouvé");
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

/**
     * Affiche la liste de tous les messages pour l'utilisateur connecté.
     * Vérifie que l'utilisateur est authentifié avant de récupérer les messages.
     * Récupère tous les messages associés à l'ID de l'utilisateur et rend
     * la vue 'messagesList' avec les données des messages récupérés.
     *
     * @return void
     */
    public function showMessagesList(): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];
        $messages = $this->messageManager->getAllMessagesByUserId($userId);
        $this->renderView('messagesList', ['messages' => $messages]);
    }

    /**
     * Envoie un message à un destinataire spécifié.
     * Vérifie que l'utilisateur est connecté et que les données du message sont valides.
     * Crée et envoie le message, puis affiche la conversation mise à jour.
     * @return void
     */
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

    /**
     * Affiche la conversation entre l'utilisateur actuel et le destinataire spécifié.
     * Vérifie que l'utilisateur est authentifié avant de procéder.
     * Récupère et prépare les données de la conversation pour l'affichage.
     * Redirige vers la page de messagerie si aucun ID de destinataire n'est fourni.
     * @return void
     */

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
    /**
     * Met à jour le statut d'un message comme lu si une requête POST valide est reçue.
     * Attend une charge utile JSON avec les champs 'id' et 'is_read'.
     * Renvoie une réponse JSON indiquant le succès ou l'échec.
     * Répond avec un code de statut HTTP 400 et un message d'erreur pour une entrée invalide.
     */

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
                    echo json_encode(['success' => false, 'error' => 'Invalid status value.']);
                    exit();
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
                exit();
            }
        }
    }
 /**
     * Récupère les données communes pour les vues de messagerie.
     * @param int $userId ID de l'utilisateur
     * @return array Données communes pour les vues
     */
    private function getCommonViewData(int $userId): array
    {
        return [
            'messages' => $this->messageManager->getMessagesByUserId($userId),
            'unreadCount' => $this->messageManager->getUnreadMessagesCount($userId),
            'lastMessages' => $this->messageManager->getLastMessagesByUserId($userId)
        ];
    }
/**
     * Rend une vue avec les données spécifiées.
     * @param string $viewName Nom de la vue à rendre
     * @param array $data Données à passer à la vue
     * @return void
     */
    private function renderView(string $viewName, array $data): void
    {
        $view = new View('Messagerie');
        $view->render($viewName, $data);
    }
}
