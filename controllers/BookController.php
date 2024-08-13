<?php

class BookController
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome(): void
    {
        $bookManager = new BookManager();
        $books = $bookManager->getBooks([], ['id' => 'DESC'], 4);

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

        // Filtrer les livres si la requête de recherche a au moins 2 caractères
        if (strlen($query) >= 2) {
            $books = $bookManager->searchBooks($query);
        } else {
            $books = $bookManager->getAllBooks();
        }

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
    
        if ($book) {
            $userManager = new UserManager();
            $owner = $userManager->getUserById($book->getUserId());
        } else {
            $owner = null;
        }
    
        $view = new View('Book Detail');
        $view->render('book-detail', ['book' => $book, 'owner' => $owner]);
    }

    /**
     * Affiche la page pour ajouter un livre.
     * @return void
     */
    public function addBook(): void
    {
        $view = new View("Ajouter un livre");
        $view->render("addbook");
    }
}
