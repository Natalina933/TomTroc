<?php

class AdminController
{
    /**
     * Affiche la page d'administration des livres.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté et a les droits nécessaires.
        $this->checkIfUserIsConnected();
        $this->checkIfUserIsAdmin();

        // On récupère la liste des livres.
        $bookManager = new BookManager();
        $books = $bookManager->getAllBooks();

        // On affiche la page d'administration des livres.
        $view = new View("Administration des Livres");
        $view->render("adminBooks", [
            'books' => $books
        ]);
    }

    /**
     * Affiche le formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        $login = Utils::request("login");
        $password = Utils::request("password");

        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $userManager = new UserManager();
        $user = $userManager->getUserByUsername($login); // Changement de méthode
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new Exception("Le mot de passe est incorrect.");
        }

        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['idUser']);

        Utils::redirect("home");
    }

    /**
     * Affiche le formulaire d'inscription.
     * @return void
     */
    public function displayRegistrationForm(): void
    {
        $view = new View("Inscription");
        $view->render("registrationForm");
    }

    /**
     * Inscrit un nouvel utilisateur.
     * @return void
     */
    public function registerUser(): void
    {
        $username = Utils::request("username");
        $email = Utils::request("email");
        $password = Utils::request("password");
        $confirmPassword = Utils::request("confirm_password");

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        if ($password !== $confirmPassword) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }

        $userManager = new UserManager();
        $existingUser = $userManager->findExistingUser(['username' => $username, 'email' => $email]);
        if ($existingUser) {
            throw new Exception("Un utilisateur avec ce nom d'utilisateur existe déjà.");
        }

        $user = new User([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => null,
            'last_name' => null,
            'profile_picture' => null,
            'birthdate' => null,
            'phone_number' => null,
            'address' => null,
            'role' => 'user',
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $userManager->registerUser($user);

        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        Utils::redirect("home");
    }

    /**
     * Affiche le formulaire de mise à jour d'un livre.
     * @return void
     */
    public function showUpdateBookForm(): void
    {
        $this->checkIfUserIsConnected();
        $this->checkIfUserIsAdmin();

        $bookId = Utils::request("id", -1);
        $bookManager = new BookManager();
        $book = $bookManager->getBookById($bookId);

        if (!$book) {
            $book = new Book(); // Création d'un nouvel objet Book si non trouvé
        }

        $view = new View("Édition d'un Livre");
        $view->render("updateBookForm", [
            'book' => $book
        ]);
    }

    /**
     * Ajoute ou met à jour un livre.
     * @return void
     */
    public function updateBook(): void
    {
        $this->checkIfUserIsConnected();
        $this->checkIfUserIsAdmin();

        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $author = Utils::request("author");
        $description = Utils::request("description");

        if (empty($title) || empty($author)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $book = new Book([
            'id' => $id,
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'added_by' => $_SESSION['idUser']
        ]);

        $bookManager = new BookManager();
        $bookManager->addOrUpdateBook($book);

        Utils::redirect("admin");
    }

    /**
     * Supprime un livre.
     * @return void
     */
    public function deleteBook(): void
    {
        $this->checkIfUserIsConnected();
        $this->checkIfUserIsAdmin();

        $id = Utils::request("id", -1);

        if ($id <= 0) {
            throw new Exception("ID du livre invalide.");
        }

        $bookManager = new BookManager();
        $bookManager->deleteBook($id);

        Utils::redirect("admin");
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Vérifie que l'utilisateur a un rôle d'administrateur.
     * @return void
     */
    private function checkIfUserIsAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            throw new Exception("Vous n'avez pas les droits nécessaires pour accéder à cette page.");
        }
    }
}
