<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private const ERROR_BOOK_NOT_FOUND = "Livre non trouvé.";
    const UPLOAD_DIR = '/assets/img/books/';
    const DEFAULT_IMAGE = '/../assets/img/defaultBook.webp';
    const ERROR_UNAUTHORIZED = "Vous devez être connecté pour effectuer cette action.";
    const ERROR_INVALID_FILE = "Le fichier téléchargé n'est pas une image valide.";
    private $bookManager;

    /**
     * BookController constructor.
     *
     * Instancie un nouveau gestionnaire de livres.
     */
    public function __construct()
    {
        $this->bookManager = new BookManager();
    }

    /**
     * Affiche la page d'accueil.
     *
     * Récupère les livres à l'échange et les envoie à la vue pour affichage.
     */
    public function showHome(): void
    {
        $books = $this->bookManager->getBooks();
        $view = new View("Accueil");
        $view->render("home", ['books' => $books]);
    }

    /**
     * Affiche la page qui liste tous les livres.
     *
     * Récupère tous les livres à l'échange et les envoie à la vue pour affichage.
     */
    public function showBooksList(): void
    {
        $books = $this->bookManager->getAllBooks();
        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books]);
    }

    /**
     * Affiche la page qui montre les détails d'un livre.
     *
     * Récupère le livre par son ID et l'envoie à la vue pour affichage.
     *
     * @param int $id L'ID du livre
     */
    public function showBookDetail(int $id): void
    {
        $book = $this->bookManager->getBookById($id);
        if (!$book) {
            // Livre non trouvé, afficher un message d'erreur
            $view = new View('Erreur');
            $view->render('error', ['message' => self::ERROR_BOOK_NOT_FOUND]);
            return;
        }

        $view = new View('Book Detail');
        $view->render('book-detail', ['book' => $book]);
    }

    /**
     * Affiche la page qui permet de modifier les informations d'un livre.
     *
     * Récupère le livre par son ID et l'envoie à la vue pour affichage.
     * Si la méthode est POST, traite les données du formulaire et met à jour le livre.
     *
     * @param int $bookId L'ID du livre
     */
    public function editBook($bookId): void
    {
        try {
            $this->ensureUserIsConnected();
            error_log("Début de la méthode editBook pour bookId: $bookId");
            var_dump($_POST, $_FILES); // Affiche les données soumises
            $book = $this->bookManager->getBookById($bookId);
            error_log("Livre récupéré : " . print_r($book, true));
            if (!$book) {
                throw new Exception(self::ERROR_BOOK_NOT_FOUND);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                error_log("Méthode POST détectée, traitement des données.");

                // Vérifie les données du formulaire
                $title = filter_input(INPUT_POST, 'title');
                $description = filter_input(INPUT_POST, 'description');
                $author = filter_input(INPUT_POST, 'author');
                $available = filter_input(INPUT_POST, 'available', FILTER_VALIDATE_INT);
                error_log("Données reçues : Titre - $title, Auteur - $author, Description - $description");

                $this->validateBookData([
                    'title' => $title,
                    'author' => $author,
                    'description' => $description
                ]);
                // Mise à jour des données
                $book->setTitle($title);
                $book->setDescription($description);
                $book->setAuthor($author);
                $book->setAvailable($available);
                $book->setUpdatedAt(date('Y-m-d H:i:s'));
                // Gestion de l'image
                if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                    $newImagePath = $this->handleImageUpload($_FILES['img']);
                    error_log("Nouvelle image téléchargée : $newImagePath");

                    if ($newImagePath) {
                        $book->setImg($newImagePath);
                    } else {
                        throw new Exception("Erreur lors de l'upload de l'image");
                    }
                } elseif (!$book->getImg()) {
                    error_log("Aucune image téléchargée. Utilisation de l'image par défaut.");

                    $book->setImg('/assets/img/defaultBook.webp');
                }

                if ($this->bookManager->editBook($book)) {
                    error_log("Livre modifié avec succès. ID: $bookId");
                    Utils::redirect("myAccount", ["status" => "success", "message" => "Livre modifié avec succès"]);
                } else {
                    throw new Exception("Erreur lors de la modification du livre");
                }
            }

            $view = new View('Book Edit');
            $view->render('book-edit', ['book' => $book]);
        } catch (Exception $e) {
            error_log("Erreur dans editBook : " . $e->getMessage());
            Utils::redirect("editBook", ["id" => $bookId, "status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Updates the image of a book.
     *
     * This function ensures the user is connected and handles the image upload process
     * for a specified book. It validates the request method, checks for file upload errors,
     * and updates the book's image in the database. If successful, it redirects to the edit page
     * with a success message; otherwise, it throws an appropriate exception and redirects to the
     * book detail page with an error message.
     *
     * @param int $bookId The ID of the book whose image is to be updated.
     *
     * @throws Exception If the user is not connected, the request method is not POST,
     *                   the book is not found, no file is uploaded, or there is an error
     *                   in image upload or database update.
     */
    public function updateBookImage($bookId): void
    {
        try {
            $this->ensureUserIsConnected();
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode non autorisée.");
            }
            $book = $this->bookManager->getBookById($bookId);
            if (!$book) {
                throw new Exception(self::ERROR_BOOK_NOT_FOUND);
            }
            error_log("Données POST : " . print_r($_POST, true));
            error_log("Données FILES : " . print_r($_FILES, true));

            if (!isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Aucun fichier n'a été téléchargé.");
            }
            $this->validateBookImage($_FILES['img']);
            $newImagePath = $this->handleImageUpload($_FILES['img']);
            if ($newImagePath) {
                $book->setImg($newImagePath);
                if ($this->bookManager->editBook($book)) {
                    Utils::redirect("editBook", ["id" => $bookId, "status" => "success", "message" => "Image du livre mise à jour avec succès"]);
                } else {
                    throw new Exception("Erreur lors de la mise à jour de l'image du livre");
                }
            } else {
                throw new Exception("Erreur lors du téléchargement de l'image");
            }
        } catch (Exception $e) {
            Utils::redirect("bookDetail", ["id" => $bookId, "status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Vérifie si le fichier téléchargé est une image valide.
     *
     * Vérifie que le type de fichier est autorisé et que la taille n'excède pas 5 Mo.
     *
     * @param array $file Les données du fichier téléchargé
     *
     * @throws Exception Si le fichier n'est pas une image valide
     */
    private function validateBookImage(array $file): void
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception(self::ERROR_INVALID_FILE);
        }

        if ($file['size'] > $maxSize) {
            throw new Exception("L'image est trop volumineuse. Taille maximale : 5 MB.");
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté
     *
     * Lève une exception si l'utilisateur n'est pas connecté
     *
     * @throws Exception Si l'utilisateur n'est pas connecté
     */
    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            throw new Exception(self::ERROR_UNAUTHORIZED);
        }
    }

    /**
     * Validates the provided book data.
     *
     * Ensures that the title, author, and description fields are not empty.
     *
     * @param array $data The book data to validate
     *
     * @throws Exception If any of the required fields are empty
     */
    private function validateBookData(array $data): void
    {
        if (empty($data['title']) || empty($data['author']) || empty($data['description'])) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    }

    /**
     * Affiche le formulaire d'ajout de livre si la requête est de type GET, ou traite le formulaire si la requête est de type POST.
     *
     * Vérifie si l'utilisateur est connecté, puis valide les données du formulaire.
     * Si les données sont valides, ajoute le livre à la base de données.
     *
     * @throws Exception Si l'utilisateur n'est pas connecté, ou si une erreur se produit lors de l'ajout du livre.
     */
    public function addBook()
    {
        $this->ensureUserIsConnected();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'] ?? null;
            if (!$userId) {
                throw new Exception("Utilisateur non connecté.");
            }

            // Validation des données
            $title = Utils::request('title');
            $author = Utils::request('author');
            $description = Utils::request('description');
            $available = Utils::request('available', 1);

            if (empty($title) || empty($author) || empty($description)) {
                Utils::redirect("addBook", ["status" => "error", "message" => "Veuillez remplir tous les champs obligatoires."]);
                return;
            }

            // Traitement de l'image
            $imagePath = $this->handleImageUpload($_FILES['image'] ?? null);

            $newBook = new Book([
                'user_id' => $userId,
                'title' => $title,
                'author' => $author,
                'img' => $imagePath,
                'description' => $description,
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s'),
                'available' => $available
            ]);

            try {
                if ($this->bookManager->addBook($newBook)) {
                    Utils::redirect("myAccount", ["status" => "success", "message" => "Livre ajouté avec succès."]);
                } else {
                    throw new Exception("Erreur lors de l'ajout du livre.");
                }
            } catch (Exception $e) {
                Utils::redirect("addBook", ["status" => "error", "message" => $e->getMessage()]);
            }
        } else {
            $view = new View('Ajouter un livre');
            $view->render('book-edit', ['book' => new Book()]);
        }
    }

    /**
     * Gère l'upload d'une image pour un livre.
     *
     * Vérifie si un fichier a été uploadé, puis si le type de fichier est autorisé.
     * Si le type de fichier est valide, déplace le fichier uploadé vers le répertoire
     * défini par la constante UPLOAD_DIR.
     *
     * @param array $file Les données du fichier uploadé
     * @return string|null Le chemin de l'image, ou null si l'upload a échoué
     * @throws Exception Si le type de fichier n'est pas valide, ou si une erreur se produit
     *                  lors du déplacement du fichier.
     */
    private function handleImageUpload($file): ?string
    {
        error_log("Début de handleImageUpload");
        var_dump($file);

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            error_log("Fichier uploadé avec succès");

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_DIR;
            $fileName = uniqid() . '_' . basename($file['name']);
            $uploadFile = $uploadDir . $fileName;
            error_log("Chemin de destination : " . $uploadFile);

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            error_log("Type de fichier : " . $imageFileType);

            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            error_log("Types autorisés : " . implode(', ', $allowedTypes));

            if (in_array($imageFileType, $allowedTypes)) {
                error_log("Type de fichier valide");

                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $imagePath = self::UPLOAD_DIR . $fileName;
                    error_log("Fichier déplacé avec succès. Nouveau chemin : " . $imagePath);
                } else {
                    error_log("Erreur lors du déplacement du fichier");
                    throw new Exception("Erreur lors du téléchargement de l'image.");
                }
            } else {
                error_log("Type de fichier non valide");
                throw new Exception(self::ERROR_INVALID_FILE);
            }
        } else {
            error_log("Aucun fichier uploadé ou erreur lors de l'upload");
            $imagePath = self::DEFAULT_IMAGE;
        }

        error_log("Fin de handleImageUpload. Chemin de l'image retourné : " . $imagePath);
        return $imagePath;
    }

    /**
     * Supprime un ou plusieurs livres en fonction des IDs fournis.
     * Récupère les IDs des livres à supprimer depuis la requête.
     * Si aucun ID n'est fourni, redirige vers "myAccount" avec un message d'erreur.
     * Appelle la méthode deleteBook du BookManager pour supprimer les livres.
     * Redirige vers "myAccount" avec un message de succès si la suppression réussit,
     * sinon redirige avec un message d'erreur.
     */
    public function deleteBook()
    {
        $bookIds = Utils::request('bookIds');
        if (!$bookIds) {
            Utils::redirect("myAccount", ["status" => "error", "message" => "Aucun livre sélectionné pour la suppression"]);
            return;
        }

        $result = $this->bookManager->deleteBook($bookIds);
        if ($result) {
            $message = is_array($bookIds) ? "Les livres ont bien été supprimés" : "Le livre a bien été supprimé";
            Utils::redirect("myAccount", ["status" => "success", "message" => $message]);
        } else {
            $message = is_array($bookIds) ? "Les livres n'ont pas été supprimés" : "Le livre n'a pas été supprimé";
            Utils::redirect("myAccount", ["status" => "error", "message" => $message]);
        }
    }

    /**
     * Affiche la page "Mon compte" avec le nombre de livres détenus par l'utilisateur.
     *
     * @return void
     */
    public function displayBooksSection(): void
    {
        $userId = $_SESSION['user']['id'];
        $bookCount = $this->bookManager->countUserBooks($userId);
        $view = new View('Mon compte');
        $view->render('myAccount', [
            'numberOfBooks' => $bookCount
        ]);
    }

    /**
     * Affiche la page "Édition d'un Livre" pour le livre identifié par l'ID fourni
     * dans la requête.
     *
     * Vérifie que l'utilisateur est connecté et possède le rôle "admin".
     * Si ce n'est pas le cas, redirige vers la page de connexion.
     *
     * @return void
     */
    public function showUpdateBookForm(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserController::ROLE_ADMIN) {
            Utils::redirect("login");
            exit;
        }
        $bookId = Utils::request("id", -1);
        $book = $this->bookManager->getBookById($bookId) ?? new Book();
        $view = new View('Édition d\'un Livre');
        $view->render('updateBookForm', ['book' => $book]);
    }
    /**
     * Displays the form for adding a new book.
     *
     * Renders the 'book-edit' view with a new Book instance.
     * This method sets up the view for adding a book, allowing
     * users to input book details.
     */
    public function displayAddBookForm()
    {
        $view = new View('Ajouter un livre');
        $view->render('book-edit', ['book' => new Book()]);
    }
}
