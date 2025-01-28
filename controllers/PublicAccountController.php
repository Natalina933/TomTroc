<?php

class PublicAccountController
{
    private const ERROR_USER_NOT_FOUND = "Utilisateur non trouvé.";
    private const VIEW_PUBLIC_ACCOUNT = "publicAccount";

    private $userManager;
    private $bookManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->bookManager = new BookManager();
    }

    public function showPublicAccount()
    {
        try {
            // Récupération de l'ID utilisateur (à sécuriser avec filter_var)
            $userId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

            $user = $this->userManager->getUserById($userId);

            if (!$user) {
                throw new Exception(self::ERROR_USER_NOT_FOUND);
            }

            $books = $this->bookManager->getAllBooksByUserId($userId);
            $totalBooks = count($books);

            // Préparation des données pour la vue
            $data = [
                'user' => $user,
                'books' => $books,
                'totalBooks' => $totalBooks,
            ];

            // Inclusion de la vue
            $this->renderView(self::VIEW_PUBLIC_ACCOUNT, "Profil de {$user->getUsername()}", $data);
        } catch (Exception $e) {
          
            Utils::redirect('error', ['message' => $e->getMessage()]);
        }
    }

    private function renderView(string $viewName, string $pageTitle, array $data = []): void
    {
        $view = new View($pageTitle);
        $view->render($viewName, $data);
    }
}
