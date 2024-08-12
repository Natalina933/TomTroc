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
        // Pas besoin de récupérer un ID spécifique ici.
        error_log("showBooksList called to display all books");

        // On récupère tous les livres
        $bookManager = new BookManager();
        $books = $bookManager->getAllBooks();

        if (empty($books)) {
            throw new Exception("Aucun livre disponible.");
        }

        // On passe la liste des livres à la vue
        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books]);
    }

    /**
     * Affiche le formulaire d'ajout d'un book.
     * @return void
     */
    public function addbook(): void
    {
        $view = new View("Ajouter un book");
        $view->render("addbook");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    public function showApropos()
    {
        $view = new View("A propos");
        $view->render("apropos");
    }
}
