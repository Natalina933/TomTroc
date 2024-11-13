<?php

class PublicAccountController
{
    private $userManager;
    private $bookManager;
    public function showPublicAccount()
    {
        // Récupération de l'ID utilisateur (à sécuriser avec filter_var)
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        // Récupération des données utilisateur et livres
        $userManager = new UserManager();
        $bookManager = new BookManager();

        $user = $userManager->getUserById($userId);

        if (!$user) {
            throw new Exception("Utilisateur non trouvé.");
        }

        $books = $bookManager->getAllBooksByUserId($userId);
        $totalBooks = count($books);

        // Préparation des données pour la vue
        $data = [
            'user' => $user,
            'books' => $books,
            'totalBooks' => $totalBooks,
        ];

        // Inclusion de la vue
        $this->renderView('publicAccount', "Profil de {$user->getUsername()}", $data);
        }

    private function renderView(string $viewName, string $pageTitle, array $data = []): void
    {
        $view = new View($pageTitle);
        $view->render($viewName, $data);
    }
}
