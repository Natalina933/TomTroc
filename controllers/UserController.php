<?php

class UserController
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    private $userManager;
    private $bookManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->bookManager = new BookManager();
    }

    public function showMyAccount(): void
    {
        $this->ensureUserIsConnected();
        $userId = $_SESSION['user']['id'];
        $bookManager = new BookManager();
        $books = $bookManager->getAllBooksByUserId($userId);
        $totalBooks = $bookManager->countUserBooks($userId);
    
        $dateFormatter = new DateFormatter();
    
        $this->renderView('myAccount', "Mon Compte", [
            'user' => (array)$_SESSION['user'],
            'books' => $books,
            'totalBooks' => $totalBooks,
            'dateFormatter' => $dateFormatter
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
        // On récupère les données du formulaire.
        $email = Utils::request("email");
        $password = Utils::request("password");

        $this->validateRequiredFields([$email, $password]);

        $user = $this->userManager->getUserByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new Exception("Identifiants incorrects.");
        }

        $this->setUserSession($user);
        Utils::redirect("myAccount");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        session_destroy();
        // On redirige vers la page d'accueil.
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

        $user = $this->userManager->createUser($username, $email, $password);
        $this->setUserSession($user);
        Utils::redirect("myAccount", ["message" => "Inscription réussie !"]);
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
        $book = $this->bookManager->getBookById($bookId) ?? new Book();
        $this->renderView('updateBookForm', "Édition d'un Livre", ['book' => $book]);
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

        $bookData = [
            'id' => Utils::request("id", -1),
            'title' => Utils::request("title"),
            'author' => Utils::request("author"),
            'description' => Utils::request("description"),
            'added_by' => $_SESSION['idUser']
        ];

        $this->validateRequiredFields([$bookData['title'], $bookData['author']]);

        $book = new Book($bookData);
        $this->bookManager->addOrUpdateBook($book);
        Utils::redirect("book-detail");
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

        $this->bookManager->deleteBook($id);
        Utils::redirect("book-detail");
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

    private function ensureUserHasRole(string $role): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            throw new Exception("Vous n'avez pas les droits nécessaires pour accéder à cette page.");
        }
    }
    public function editUser(): void
    {
        $this->ensureUserIsConnected();
        
        $userId = $_SESSION['user']['id'];
        $username = Utils::request("username");
        $email = Utils::request("email");
        
        $this->validateRequiredFields([$username, $email]);
        
        $user = $this->userManager->getUserById($userId);
        if (!$user) {
            throw new Exception("Utilisateur non trouvé.");
        }
        
        $user->setUsername($username);
        $user->setEmail($email);
        
        if ($this->userManager->editUser($user)) {
            $this->updateUserSession($user);
            Utils::redirect("myAccount", ["message" => "Profil mis à jour avec succès."]);
        } else {
            throw new Exception("Erreur lors de la mise à jour du profil.");
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

    private function setUserSession(User $user): void
    {
        $_SESSION['user'] = [
            'id' => $user->getId(),
            "role" => $user->getRole(),
            "email" => $user->getEmail(),
            "username" => $user->getUsername(),
            "createdAt" => $user->getCreatedAt(),
            "profilePicture" => $user->getProfilePicture()
        ];
        $_SESSION['idUser'] = $user->getId();
    }

    private function updateUserSession(User $user): void
    {
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'role' => $user->getRole(),
            'profilePicture' => $user->getProfilePicture(),
            'createdAt' => $user->getCreatedAt(),
        ];
    }

    private function validateProfilePicture(array $file): void
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
        $maxFileSize = 5 * 1024 * 1024; // 5 Mo

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            Utils::redirect("myAccount", ["error" => "Type de fichier non autorisé."]);
        }

        if ($file['size'] > $maxFileSize) {
            Utils::redirect("myAccount", ["error" => "Le fichier est trop volumineux."]);
        }
    }

    private function moveUploadedFile(array $file): string
    {
        $uploadDir = '/assets/img/users/';
        $newFileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], __DIR__ . "/.." . $destPath)) {
            Utils::redirect("myAccount", ["error" => "Erreur lors du déplacement du fichier."]);
        }

        return $destPath;
    }

    private function updateUserProfilePicture(string $newFileName): void
    {
        $userId = $_SESSION['user']['id'];
        if (!$this->userManager->updateProfilePicture($userId, $newFileName)) {
            Utils::redirect("myAccount", ["error" => "Erreur lors de la mise à jour de la base de données."]);
        }
        $_SESSION['user']['profilePicture'] = $newFileName;
    }

    private function handleUploadError(int $errorCode): void
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => "Le fichier est trop volumineux.",
            UPLOAD_ERR_FORM_SIZE => "Le fichier est trop volumineux.",
            UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement téléchargé.",
            UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été téléchargé.",
            UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire manquant.",
            UPLOAD_ERR_CANT_WRITE => "Erreur d'écriture du fichier sur le disque.",
            UPLOAD_ERR_EXTENSION => "Téléchargement de fichier arrêté par une extension PHP.",
        ];

        $errorMessage = $errorMessages[$errorCode] ?? "Erreur inconnue lors du téléchargement.";
        Utils::redirect("myAccount", ["error" => $errorMessage]);
    }
}
