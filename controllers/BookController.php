<?php

class bookController
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome(): void
    {
        $bookManager = new bookManager();
        $books = $bookManager->getBooks([], ['id' => 'DESC'], 4);

        $view = new View("Accueil");
        $view->render("home", ['books' => $books]);
    }


    /**
     * Affiche le détail d'un book.
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

        if (empty($books)) {
            throw new Exception("Aucun livre disponible.");
        }

        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books]);
    }

    public function addbook(): void
    {
        $view = new View("Ajouter un book");
        $view->render("addbook");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
}
