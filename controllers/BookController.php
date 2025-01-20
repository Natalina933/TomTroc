<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private const ERROR_BOOK_NOT_FOUND = "Livre non trouvé.";

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

    public function showHome(): void
    {
        $books = $this->bookManager->getBooks();
        $view = new View("Accueil");
        $view->render("home", ['books' => $books]);
    }

    public function showBooksList(): void
    {
        $books = $this->bookManager->getAllBooks();
        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books]);
    }

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

                // Vérifiez les données du formulaire
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
    private function validateBookImage(array $file): void
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.");
        }

        if ($file['size'] > $maxSize) {
            throw new Exception("L'image est trop volumineuse. Taille maximale : 5 MB.");
        }
    }


    private function validateBookData(array $data): void
    {
        if (empty($data['title']) || empty($data['author']) || empty($data['description'])) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    }



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

    private function handleImageUpload($file): ?string
    {
        error_log("Début de handleImageUpload");
        var_dump($file);
        // $imagePath = '/assets/img/defaultBook.webp'; // Image par défaut

        // error_log("Image par défaut : " . $imagePath);

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            error_log("Fichier uploadé avec succès");

            $uploadDir = 'assets/img/books/';
            $uploadFile = $uploadDir . uniqid() . '_' . basename($file['name']);
            error_log("Chemin de destination : " . $uploadFile);

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            error_log("Type de fichier : " . $imageFileType);

            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            error_log("Types autorisés : " . implode(', ', $allowedTypes));

            if (in_array($imageFileType, $allowedTypes)) {
                error_log("Type de fichier valide");

                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $imagePath = '/' . $uploadFile;
                    error_log("Fichier déplacé avec succès. Nouveau chemin : " . $imagePath);
                } else {
                    error_log("Erreur lors du déplacement du fichier");
                    throw new Exception("Erreur lors du téléchargement de l'image.");
                }
            } else {
                error_log("Type de fichier non valide");
                throw new Exception("Le fichier téléchargé n'est pas une image valide.");
            }
        } else {
            error_log("Aucun fichier uploadé ou erreur lors de l'upload");
        }

        error_log("Fin de handleImageUpload. Chemin de l'image retourné : " . $imagePath);
        return $imagePath;
    }

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


    public function displayBooksSection(): void
    {
        $userId = $_SESSION['user']['id'];
        $bookCount = $this->bookManager->countUserBooks($userId);
        $view = new View('Mon compte');
        $view->render('myAccount', [
            'numberOfBooks' => $bookCount
        ]);
    }

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
    public function displayAddBookForm()
    {
        $view = new View('Ajouter un livre');
        $view->render('book-edit', ['book' => new Book()]);
    }
    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("login");
        }
    }
}
