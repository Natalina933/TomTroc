<?php

class BookController
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome(): void
    {
        // Instanciation du manager des books
        $bookManager = new BookManager();

        // Récupérer tous les livres
        $books = $bookManager->getBooks();
        // Affichage de la vue avec les données récupérées
        $view = new View("Accueil");
        $view->render("home", ['books' => $books]);
    }

    /**
     * Affiche la liste des livres, avec une recherche si nécessaire.
     * @return void
     */
    public function showBooksList(): void
    {
        $query = Utils::request('query', '');
        // Nettoyer la requête pour éviter les injections SQL
        $query = trim($query);
        $bookManager = new BookManager();


        $books = $bookManager->getAllBooks();

        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books]);
    }

    /**
     * Affiche les détails d'un livre.
     * @param int $id Identifiant du livre
     * @return void
     */
    public function showBookDetail(int $id): void
    {
        $bookManager = new BookManager();
        $book = $bookManager->getBookById($id);

        $view = new View('Book Detail');
        $view->render('book-detail', ['book' => $book]);
    }

    /**
     * Affiche la page pour ajouter un livre.
     * @return void
     */

    public function editBook($bookId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
        // Récupérer l'objet Book

        $bookManager = new BookManager();
        $book = $bookManager->getBookById($bookId);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = filter_input(INPUT_POST, 'id');
            $title = filter_input(INPUT_POST, 'title');
            $description = filter_input(INPUT_POST, 'description');
            $author = filter_input(INPUT_POST, 'author');
            $available = isset($_POST['available']) ? 1 : 0;

            $img = $_FILES['img']['name'] ?? null;


            if ($book) {
                $book->setTitle($title);
                $book->setDescription($description);
                $book->setAuthor($author);
                $book->setAvailable($available);
                $book->setUpdatedAt(date('Y-m-d H:i:s'));

                if ($img && !empty($_FILES['img']['tmp_name'])) {
                    $uploadDir = './assets/img/books/';
                    $newFileName = uniqid('book_', true) . '.' . pathinfo($img, PATHINFO_EXTENSION);
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['img']['tmp_name'], $destPath)) {
                        $book->setImg($newFileName);
                    }
                }

                if ($bookManager->editBook($book)) {
                    header('Location: index.php?action=viewBook&id=' . $bookId . '&status=success');
                } else {
                    header('Location: index.php?action=editBook&id=' . $bookId . '&status=error');
                }
            } else {
                header('Location: index.php?action=editBook&id=' . $bookId . '&status=not_found');
            }
            exit;
        }

        $view = new View('Book Edit');
        $view->render('book-edit', ['book' => $book]);
    }

    /**
     * Ajoute un nouveau livre.
     * @return void
     */
    public function addBook(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $title = Utils::request('title');
            $author = Utils::request('author');
            $description = Utils::request('description');
            $available = Utils::request('available', 1);
            $userId = $_SESSION['user']['id'];

            // Créer un nouvel objet Book
            $newBook = new Book([
                'title' => $title,
                'author' => $author,
                'description' => $description,
                'available' => $available,
                'added_by' => $userId
            ]);

            // Ajouter le livre via BookManager
            $bookManager = new BookManager();
            if ($bookManager->addOrUpdateBook($newBook)) {
                Utils::redirect("myAccount", ["status" => "success", "message" => "Livre ajouté avec succès"]);
            } else {
                Utils::redirect("addBook", ["status" => "error", "message" => "Erreur lors de l'ajout du livre"]);
            }
        } else {
            // Afficher le formulaire d'ajout de livre
            $view = new View('Ajouter un livre');
            $view->render('book-edit', ['book' => new Book()]);
        }
    }

    public function deleteBook()
    {
        // Récupérer les IDs des books à supprimer à partir des données POST
        $bookIds = Utils::request('bookIds');
        // Instancier le gestionnaire de commentaires
        $bookManager = new BookManager();
        // Supprimer le book
        $result = $bookManager->deleteBook($bookIds);
        if ($result) {
            throw new Exception("Le livre a bien été supprimé");

            //redirect vers la page myaccount
            Utils::redirect("myaccount");
        } else {
            throw new Exception("Le livre n'a pas été supprimé");
        }
    }
    public function displayBooksSection(): void
    {
        // Récupérer l'ID de l'utilisateur actuellement connecté (à adapter selon votre contexte)
        $userId = $_SESSION['user']['id'];

        // Récupérer le BookManager
        $bookManager = new BookManager();

        // Compter le nombre de livres et stocker le résultat dans une variable
        $bookCount = $bookManager->countUserBooks($userId);

        // Passer les données à la vue
        $view = new View('Mon compte');
        $view->render('myAccount',  [
            'numberOfBooks' => $bookCount
        ]);
    }
}
