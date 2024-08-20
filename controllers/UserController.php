<?php

class UserController
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * Affiche la page d'administration des livres.
     * @return void
     */
    public function showAdmin(): void
    {
        $this->ensureUserIsConnected();
        $this->ensureUserHasRole(self::ROLE_ADMIN);

        $bookManager = new BookManager();
        $books = $bookManager->getAllBooks();

        $this->renderView('adminBooks', "Administration des Livres", [
            'books' => $books
        ]);
    }

    /**
     * Affiche le formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $this->renderView('connectionForm', "Connexion");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     * @throws Exception
     */
    public function connectUser(): void
    {
        $login = Utils::request("login");
        $password = Utils::request("password");

        $this->validateRequiredFields([$login, $password]);

        $userManager = new UserManager();
        $user = $userManager->getUserByUsername($login);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new Exception("Identifiants incorrects.");
        }

        $this->setUserSession($user);
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        $this->clearUserSession();
        Utils::redirect("home");
    }

    /**
     * Affiche le formulaire d'inscription.
     * @return void
     */
    public function displayRegistrationForm(): void
    {
        $this->renderView('registrationForm', "Inscription");
    }

    /**
     * Inscrit un nouvel utilisateur.
     * @return void
     * @throws Exception
     */
    public function registerUser(): void
    {
        $username = Utils::request("username");
        $email = Utils::request("email");
        $password = Utils::request("password");

        $this->validateRequiredFields([$username, $email, $password]);

        $userManager = new UserManager();
        $user = $userManager->createUser($username, $email, $password);

        $_SESSION['user'] = $user;
        Utils::redirect("myAccount", ["message" => "Inscription réussie !"]);
    }

    public function showMyAccount(): void
    {
        $this->ensureUserIsConnected();

        $user = $_SESSION['user'];

        $this->renderView('myAccount', "Mon Compte", [
            'user' => $user
        ]);
    }
    /**
     * Affiche le formulaire de mise à jour d'un livre.
     * @return void
     */
    public function showUpdateBookForm(): void
    {
        $this->ensureUserIsConnected();
        $this->ensureUserHasRole(self::ROLE_ADMIN);

        $bookId = Utils::request("id", -1);
        $bookManager = new BookManager();
        $book = $bookManager->getBookById($bookId) ?? new Book();

        $this->renderView('updateBookForm', "Édition d'un Livre", [
            'book' => $book
        ]);
    }

    /**
     * Ajoute ou met à jour un livre.
     * @return void
     * @throws Exception
     */
    public function updateBook(): void
    {
        $this->ensureUserIsConnected();
        $this->ensureUserHasRole(self::ROLE_ADMIN);

        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $author = Utils::request("author");
        $description = Utils::request("description");

        $this->validateRequiredFields([$title, $author]);

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
     * @throws Exception
     */
    public function deleteBook(): void
    {
        $this->ensureUserIsConnected();
        $this->ensureUserHasRole(self::ROLE_ADMIN);

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
    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Vérifie que l'utilisateur a un rôle spécifique.
     * @param string $role
     * @throws Exception
     */
    private function ensureUserHasRole(string $role): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== $role) {
            throw new Exception("Vous n'avez pas les droits nécessaires pour accéder à cette page.");
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
     * Définit la session utilisateur.
     * @param User $user
     * @return void
     */
    private function setUserSession(User $user): void
    {
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();
    }

    /**
     * Efface la session utilisateur.
     * @return void
     */
    private function clearUserSession(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['idUser']);
    }

    /**
     * Valide que tous les champs requis sont remplis.
     * @param array $fields
     * @throws Exception
     */
    private function validateRequiredFields(array $fields): void
    {
        foreach ($fields as $field) {
            if (empty($field)) {
                throw new Exception("Tous les champs sont obligatoires.");
            }
        }
    }

    /**
     * Valide que les mots de passe correspondent.
     * @param string $password
     * @param string $confirmPassword
     * @throws Exception
     */
    private function validatePasswordsMatch(string $password, string $confirmPassword): void
    {
        if ($password !== $confirmPassword) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }
    }

    /**
     * Vérifie que le nom d'utilisateur et l'email sont uniques.
     * @param UserManager $userManager
     * @param string $username
     * @param string $email
     * @throws Exception
     */
    private function ensureUsernameAndEmailAreUnique(UserManager $userManager, string $username, string $email): void
    {
        $existingUser = $userManager->findExistingUser(['username' => $username, 'email' => $email]);
        if ($existingUser) {
            throw new Exception("Un utilisateur avec ce nom d'utilisateur ou cet email existe déjà.");
        }
    }

    /**
     * Crée un nouvel utilisateur.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    private function createUser(string $username, string $email, string $password): User
    {
        return new User([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => null,
            'last_name' => null,
            'profile_picture' => null,
            'birthdate' => null,
            'phone_number' => null,
            'address' => null,
            'role' => self::ROLE_USER,
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
