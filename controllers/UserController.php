<?php

class UserController
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    public function showMyAccount(): void
    {
        $this->ensureUserIsConnected(); // Vérifie d'abord si l'utilisateur est connecté
        // var_dump($_SESSION);
        $userId = $_SESSION['idUser'];

        $bookManager = new BookManager();
        $books = $bookManager->getAllBooksByUserId($userId);

        $this->renderView('myAccount', "Mon Compte", [
            'user' => (array)$_SESSION['user'],
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
        // On récupère les données du formulaire.
        $email = Utils::request("email");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($email) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByEmail($email);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }
        $_SESSION['user'] = [
            'id' => $user->getId(),
            "role" => $user->getRole(),
            "email" => $user->getEmail(),
            "username" => $user->getUsername(),
            "createdAt" => $user->getCreatedAt(),
            "profilePicture" => $user->getProfilePicture()
        ];
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
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

        $userManager = new UserManager();

        $user = $userManager->createUser($username, $email, $password);
        $_SESSION['user'] = $user;

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
        if (!isset($_SESSION['user'])) { // Vérification de la session utilisateur
            Utils::redirect("connectionForm"); // Redirection si l'utilisateur n'est pas connecté
            exit; // On arrête l'exécution pour éviter tout comportement indésirable
        }
    }


    /**
     * Vérifie que l'utilisateur a un rôle spécifique.
     * @param string $role
     * @throws Exception
     */
    private function ensureUserHasRole(string $role): void
    {
        if (isset($_SESSION['user']) || $_SESSION['user']->getRole() !== $role) {
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
     * Crée un nouvel utilisateur.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */

    public function updateProfilePicture()
    {
        // Vérifier si un fichier est téléchargé
        if (($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
            $fileName = $_FILES['profilePicture']['name'];
            $fileSize = $_FILES['profilePicture']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            var_dump($fileTmpPath);
            // Générer un nom de fichier unique pour éviter les conflits
            $newFileName = uniqid('profile_', true) . '.' . $fileExtension;

            // Taille maximale autorisée (exemple : 5 Mo)
            $maxFileSize = 5 * 1024 * 1024; // 5 Mo

            // Définir les extensions autorisées
            $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'svg'];

            // Vérifier l'extension du fichier
            if (!in_array($fileExtension, $allowedfileExtensions)) {
                $error = "Type de fichier non autorisé. Veuillez télécharger un fichier au format jpg, jpeg, png ou svg.";
                header('Location: index.php?action=myAccount&status=invalid_file_type&error=' . urlencode($error));
                exit;
            }

            // Vérifier la taille du fichier
            if ($fileSize > $maxFileSize) {
                $error = "Le fichier est trop volumineux. La taille maximale autorisée est de 5 Mo.";
                header('Location: index.php?action=myAccount&status=invalid_file_size&error=' . urlencode($error));
                exit;
            }

            // Dossier de stockage de l'image
            $uploadFileDir = '/assets/img/users/';
            $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;
            // Déplacer le fichier téléchargé dans le dossier de destination
            if (copy($fileTmpPath, __DIR__ . "/.."  . $dest_path)) {
                // Mettre à jour la photo de profil dans la base de données
                $userId = $_SESSION['user']['id']; // L'utilisateur est authentifié
                $userModel = new UserManager();

                // Appel à la méthode qui met à jour la photo de profil
                if ($userModel->updateProfilePicture($userId, $dest_path)) {
                    // Mettre à jour la session avec la nouvelle image
                    $_SESSION['user']['profilePicture'] = $dest_path;

                    // Redirection avec succès
                    header('Location: index.php?action=myAccount&status=success');
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour de la base de données.";
                    header('Location: index.php?action=myAccount&status=db_error&error=' . urlencode($error));
                    exit;
                }
            } else {
                $error = "Erreur lors du déplacement du fichier. Veuillez réessayer.";
                header('Location: index.php?action=myAccount&status=move_error&error=' . urlencode($error));
                exit;
            }
        } else {
            // Gérer les différentes erreurs de téléchargement
            switch ($_FILES['profilePicture']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error = "Le fichier est trop volumineux.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = "Le fichier n'a été que partiellement téléchargé.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error = "Aucun fichier n'a été téléchargé.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = "Dossier temporaire manquant.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error = "Erreur d'écriture du fichier sur le disque.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error = "Téléchargement de fichier arrêté par une extension PHP.";
                    break;
                default:
                    $error = "Erreur inconnue lors du téléchargement.";
                    break;
            }

            header('Location: index.php?action=myAccount&status=upload_error&error=' . urlencode($error));
            exit;
        }
    }
    public function editUser()
    {
        // Récupérer les données du formulaire
        $email = $_POST['email'] ?? '';
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
        $username = $_POST['username'] ?? '';
        $userId = $_SESSION['user']['id']; // L'utilisateur actuel

        // Validation simple (exemple : email déjà existant)
        $userManager = new UserManager();
        if ($userManager->emailExists($email, $userId)) {
            $error = "Cette adresse email est déjà utilisée.";
            header('Location: index.php?action=myAccount&error=' . urlencode($error));
            exit;
        }

        // Récupérer l'utilisateur à partir de son ID
        $user = $userManager->getUserById($userId);

        if ($user) {
            // Mettre à jour les informations de l'utilisateur
            $user->setEmail($email);
            if (!empty($password)) {
                $user->setPassword($password);
            }
            $user->setUsername($username);

            // Enregistrer les modifications
            $updated = $userManager->editUser($user);

            if ($updated) {
                // Redirection avec succès
                header('Location: index.php?action=myAccount&status=success');
                exit;
            } else {
                // Redirection en cas d'erreur dans la mise à jour
                $error = "Erreur lors de la mise à jour des informations.";
                header('Location: index.php?action=myAccount&error=' . urlencode($error));
                exit;
            }
        } else {
            // Si l'utilisateur n'est pas trouvé
            $error = "Utilisateur introuvable.";
            header('Location: index.php?action=myAccount&error=' . urlencode($error));
            exit;
        }
    }
}
