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

    /**
     * Affiche la page de compte public pour un utilisateur spécifique.
     *
     * Récupère l'ID de l'utilisateur à partir de la requête GET, obtient les détails de l'utilisateur
     * et ses livres depuis la base de données, et prépare les données pour le rendu de la vue.
     * Si l'utilisateur n'est pas trouvé, une exception est levée et l'utilisateur est redirigé
     * vers une page d'erreur.
     *
     * @throws Exception si l'utilisateur n'est pas trouvé.
     */

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

    /**
     * Rend une vue avec un titre de page et des données spécifiés.
     *
     * @param string $viewName Le nom de la vue à rendre
     * @param string $pageTitle Le titre de la page
     * @param array $data Les données à passer à la vue (optionnel)
     */

    private function renderView(string $viewName, string $pageTitle, array $data = []): void
    {
        $view = new View($pageTitle);
        $view->render($viewName, $data);
    }
}
