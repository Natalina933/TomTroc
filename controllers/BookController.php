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
        $userManager = new UserManager();
        $user = $userManager->getUserByUsername($id);

        if ($book) {
            $userManager = new UserManager();
            $user = $userManager->getUserById($book->getUserId());
        } else {
            $user = null;
        }

        $view = new View('Book Detail');
        $view->render('book-detail', ['book' => $book, 'user' => $user]);
    }

    /**
     * Affiche la page pour ajouter un livre.
     * @return void
     */

    public function editBook(): void
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier si les données du formulaire sont soumises
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer l'ID du livre à modifier depuis les données POST
            $bookId = filter_input(INPUT_POST, 'id');

            // Récupérer les données du formulaire de modification
            $title = filter_input(INPUT_POST, 'title');
            $description = filter_input(INPUT_POST, 'description');
            $author = filter_input(INPUT_POST, 'author');
            $img = $_FILES['img']['name'] ?? null;
            $available = isset($_POST['available']) ? 1 : 0;

            // Vérifier si l'image a été téléchargée
            if ($img && !empty($_FILES['img']['tmp_name'])) {
                $uploadDir = './assets/img/books/';
                $newFileName = uniqid('book_', true) . '.' . pathinfo($img, PATHINFO_EXTENSION);
                $destPath = $uploadDir . $newFileName;

                // Déplacer le fichier téléchargé vers le dossier de destination
                if (!move_uploaded_file($_FILES['img']['tmp_name'], $destPath)) {
                    header('Location: index.php?action=editBook&id=' . $bookId . '&status=error_upload');
                    exit;
                }
            } else {
                $newFileName = null;
            }

            // Récupérer l'objet Book depuis le BookManager
            $bookManager = new BookManager();
            $book = $bookManager->getBookById($bookId);

            if ($book) {
                // Mettre à jour les informations du livre
                $book->setTitle($title);
                $book->setDescription($description);
                $book->setAuthor($author);
                $book->setAvailable($available);
                $book->setUpdatedAt(date('Y-m-d H:i:s'));

                // Si une nouvelle image a été téléchargée, mettre à jour l'image
                if ($newFileName) {
                    $book->setImg($newFileName);
                }

                // Enregistrer les modifications dans la base de données
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

        // Si la requête n'est pas POST, rediriger vers la page de modification
        header('Location: index.php?action=editBook');
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
