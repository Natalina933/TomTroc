<?php

class UserController
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const UPLOAD_DIR = '/../assets/img/users/';
    const DEFAULT_PROFILE_PICTURE = '/../assets/img/users/profile-default.svg';
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 Mo
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'svg'];

    const UPLOAD_ERRORS = [
        UPLOAD_ERR_INI_SIZE => "Le fichier est trop volumineux.",
        UPLOAD_ERR_FORM_SIZE => "Le fichier est trop volumineux.",
        UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement téléchargé.",
        UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été téléchargé.",
        UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire manquant.",
        UPLOAD_ERR_CANT_WRITE => "Erreur d'écriture du fichier sur le disque.",
        UPLOAD_ERR_EXTENSION => "Téléchargement de fichier arrêté par une extension PHP.",
    ];
    private $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
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

    public function displayConnectionForm(): void
    {
        $this->renderView('connectionForm', "Connexion");
    }

    public function connectUser(): void
    {
        $email = Utils::request("email");
        $password = Utils::request("password");
        $this->validateRequiredFields([$email, $password]);

        $user = $this->userManager->getUserByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new Exception("Identifiants incorrects.");
        }

        $this->setUserSession($user);
        // Mettre à jour le nombre de messages non lus
        $messageManager = new MessageManager();
        $_SESSION['unreadCount'] = $messageManager->getUnreadMessagesCount($user->getId());

        // Rediriger vers la page "Mon compte"
        Utils::redirect("myAccount");
    }

    public function disconnectUser(): void
    {
        session_destroy();
        Utils::redirect("home");
    }

    public function displayRegistrationForm(): void
    {
        $this->renderView('registrationForm', "Inscription");
    }

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

    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    public function displayAddBookForm()
    {
        $this->ensureUserIsConnected();

        $view = new View("Ajouter un livre");
        $view->render("addBookForm");
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
    public function updateProfilePicture(): void
    {
        try {
            $this->ensureUserIsConnected();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode non autorisée.");
            }

            if (!isset($_FILES['profilePicture']) || $_FILES['profilePicture']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new Exception("Aucun fichier n'a été téléchargé.");
            }

            if ($_FILES['profilePicture']['error'] !== UPLOAD_ERR_OK) {
                $this->handleUploadError($_FILES['profilePicture']['error']);
            }

            $this->validateProfilePicture($_FILES['profilePicture']);

            $newFileName = $this->moveUploadedFile($_FILES['profilePicture']);

            $this->updateUserProfilePicture($newFileName);

            Utils::redirect("myAccount", ["success" => "Photo de profil mise à jour avec succès."]);
        } catch (Exception $e) {
            Utils::redirect("myAccount", ["error" => $e->getMessage()]);
        }
    }

    private function validateProfilePicture(array $file): void
    {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, self::ALLOWED_EXTENSIONS)) {
            throw new Exception("Type de fichier non autorisé.");
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("Le fichier est trop volumineux.");
        }
    }

    private function moveUploadedFile(array $file): string
    {
        $newFileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destPath = __DIR__ . self::UPLOAD_DIR . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception("Erreur lors du déplacement du fichier.");
        }

        return self::UPLOAD_DIR . $newFileName;
    }

    private function updateUserProfilePicture(string $newFileName): void
    {
        $userId = $_SESSION['user']['id'];

        // Vérifie si l'image existe
        $destPath = __DIR__ . self::UPLOAD_DIR . $newFileName;
        if (!file_exists($destPath)) {
            throw new Exception("Erreur lors du téléchargement de l'image de profil.");
        }

        if (!$this->userManager->updateProfilePicture($userId, $newFileName)) {
            throw new Exception("Erreur lors de la mise à jour de la base de données.");
        }

        $_SESSION['user']['profilePicture'] = $newFileName;
    }


    private function handleUploadError(int $errorCode): void
    {
        throw new Exception(self::UPLOAD_ERRORS[$errorCode] ?? "Erreur inconnue lors du téléchargement.");
    }

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
            'role' => $user->getRole(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'createdAt' => $user->getCreatedAt(),
            'profilePicture' => $user->getProfilePicture(),
        ];
        $_SESSION['idUser'] = $user->getId();
    }
    private function renderView(string $viewName, string $pageTitle, array $data = []): void
    {
        $view = new View($pageTitle);
        $view->render($viewName, $data);
    }
}
