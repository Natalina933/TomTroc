<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private const ERROR_BOOK_NOT_FOUND = "Livre non trouvé.";

    private $bookManager;

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
        $view = new View('Book Detail');
        $view->render('book-detail', ['book' => $book]);
    }

    public function editBook($bookId): void
    {
        try {
            $this->ensureUserIsConnected();
            $book = $this->bookManager->getBookById($bookId);
            if (!$book) {
                throw new Exception(self::ERROR_BOOK_NOT_FOUND);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = filter_input(INPUT_POST, 'title');
                $description = filter_input(INPUT_POST, 'description');
                $author = filter_input(INPUT_POST, 'author');
                $available = isset($_POST['available']) ? 1 : 0;

                $this->validateBookData([
                    'title' => $title,
                    'author' => $author,
                    'description' => $description
                ]);

                $book->setTitle($title);
                $book->setDescription($description);
                $book->setAuthor($author);
                $book->setAvailable($available);
                $book->setUpdatedAt(date('Y-m-d H:i:s'));
                // Gestion de l'upload d'image
                if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
                    $newImagePath = $this->handleImageUpload($_FILES['img']);
                    if ($newImagePath) {
                        $book->setImg($newImagePath);
                    }
                }

                if ($this->bookManager->editBook($book)) {
                    Utils::redirect("myAccount", ["status" => "success", "message" => "Livre modifié avec succès"]);
                } else {
                    throw new Exception("Erreur lors de la modification du livre");
                }
            }

            $view = new View('Book Edit');
            $view->render('book-edit', ['book' => $book]);
        } catch (Exception $e) {
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
            if (!isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Aucun fichier n'a été téléchargé.");
            }
            $this->validateBookImage($_FILES['img']);
            $newImagePath = $this->handleImageUpload($_FILES['img']);
            if ($newImagePath) {
                $book->setImg($newImagePath);
                if ($this->bookManager->editBook($book)) {
                    Utils::redirect("bookDetail", ["id" => $bookId, "status" => "success", "message" => "Image du livre mise à jour avec succès"]);
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

    private function handleImageUpload($file): ?string
    {
        $uploadDir = 'assets/img/books/';
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            return '/' . $uploadFile;
        }

        return null;
    }


    public function addBook()
    {
        $this->ensureUserIsConnected();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'] ?? null;
            if (!$userId) {
                throw new Exception("Utilisateur non connecté.");
            }
            error_log("ID utilisateur récupéré : " . $userId);
            $newBook = new Book([
                'user_id' => $userId,
                'title' => Utils::request('title'),
                'author' => Utils::request('author'),
                'description' => Utils::request('description'),
                'available' => Utils::request('available', 1),
                'img' => '/assets/img/defaultBook.png',
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s')
            ]);
            error_log("Nouvel objet Book: " . print_r($newBook, true));
            try {
                if ($this->bookManager->addBook($newBook)) {
                    Utils::redirect("myAccount", ["status" => "success", "message" => "Livre ajouté avec succès."]);
                }
            } catch (Exception $e) {
                Utils::redirect("addBook", ["status" => "error", "message" => $e->getMessage()]);
            }
        } else {
            $view = new View('Ajouter un livre');
            $view->render('book-edit', ['book' => new Book()]);
        }
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
