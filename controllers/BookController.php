<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private const UPLOAD_DIR = './assets/img/books/';
    private const ERROR_BOOK_NOT_FOUND = "Livre non trouvé.";
    private const ERROR_EDIT_FAILED = "Échec de la modification du livre.";
    private const SUCCESS_EDIT = "Livre modifié avec succès";

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
        // $query = trim(Utils::request('query', ''));
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
        // var_dump("Entering editBook method in BookController");
        // var_dump($bookId);
        try {
            $this->ensureUserIsConnected();
            $book = $this->bookManager->getBookById($bookId);
            // var_dump($book);
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

                if (isset($_FILES['img'])) {
                    $newFileName = $this->handleImageUpload($_FILES['img']);
                    if ($newFileName) {
                        $book->setImg($newFileName);
                    }
                }

                if ($this->bookManager->editBook($book)) {
                    Utils::redirect("myAccount", ["status" => "success", "message" => self::SUCCESS_EDIT]);
                } else {
                    throw new Exception(self::ERROR_EDIT_FAILED);
                }
            }

            $view = new View('Book Edit');
            $view->render('book-edit', ['book' => $book]);
        } catch (Exception $e) {
            Utils::redirect("editBook", ["id" => $bookId, "status" => "error", "message" => $e->getMessage()]);
        }
    }

    private function validateBookData(array $data): void
    {
        if (empty($data['title']) || empty($data['author']) || empty($data['description'])) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    }

    private function handleImageUpload(array $file): ?string
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $newFileName = uniqid('book_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $destPath = self::UPLOAD_DIR . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                return $newFileName;
            }
        }
        return null;
    }

    private function ensureUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("login");
        }
    }

    public function addBook(): void
    {
        try {
            $this->ensureUserIsConnected(); // Vérifie que l'utilisateur est connecté
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    // Récupération des données du formulaire
                    $title = Utils::request('title');
                    $author = Utils::request('author');
                    $description = Utils::request('description');
                    $available = Utils::request('available', 1);
                    $userId = $_SESSION['user']['id']; // Récupération de l'utilisateur connecté
                    error_log("Données reçues : " . print_r([
                        'title' => $title,
                        'author' => $author,
                        'description' => $description,
                        'available' => $available,
                        'userId' => $userId
                    ], true));
    
                    // Validation des données
                    $this->validateBookData([
                        'title' => $title,
                        'author' => $author,
                        'description' => $description
                    ]);

                    // Création de l'objet Book
                    $newBook = new Book([
                        'title' => $title,
                        'author' => $author,
                        'description' => $description,
                        'available' => (bool)$available,
                        'userId' => $userId,
                    ]);

                    // Gestion de l'upload de l'image
                    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                        $newFileName = $this->handleImageUpload($_FILES['img']);
                        if ($newFileName) {
                            $newBook->setImg($newFileName);
                        }
                    } else {
                        $newBook->setImg('/assets/img/defaultBook.png'); // Nom par défaut
                    }

                    // Vérifiez les données avant l'insertion
                    var_dump([
                        'user_id' => $newBook->getUserId(),
                        'title' => $newBook->getTitle(),
                        'author' => $newBook->getAuthor(),
                        'img' => $newBook->getImg(),
                        'description' => $newBook->getDescription(),
                        'available' => $newBook->isAvailable()
                    ]);

                    // Sauvegarde du livre via le BookManager
                    if ($this->bookManager->addOrUpdateBook($newBook)) {
                        Utils::redirect("myAccount", ["status" => "success", "message" => "Livre ajouté avec succès."]);
                    } else {
                        throw new Exception("Erreur lors de l'ajout du livre.");
                        die();
                    }
                } catch (Exception $e) {
                    var_dump("Exception: " . $e->getMessage());
                    Utils::redirect("addBook", ["status" => "error", "message" => $e->getMessage()]);
                }
            } else {
                // Afficher le formulaire d'ajout de livre si ce n'est pas une requête POST
                $view = new View('Ajouter un livre');
                $view->render('book-edit', ['book' => new Book()]);
            }
        } catch (Exception $e) {
            // Gestion des exceptions non capturées
            Utils::redirect("error", ["message" => "Une erreur inattendue s'est produite."]);
        }
    }


    public function deleteBook()
    {
        $bookIds = Utils::request('bookIds');
        $result = $this->bookManager->deleteBook($bookIds);
        if ($result) {
            Utils::redirect("myAccount", ["status" => "success", "message" => "Le livre a bien été supprimé"]);
        } else {
            Utils::redirect("myAccount", ["status" => "error", "message" => "Le livre n'a pas été supprimé"]);
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
}
