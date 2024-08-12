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
        // Récupérer le terme de recherche
        $query = Utils::request('query', '');
        // Nettoyer la requête pour éviter les injections SQL
        $query = trim($query);

        // Instancier le BookManager
        $bookManager = new BookManager();

        // Récupérer les livres en fonction de la requête de recherche
        if ($query) {
            // Effectuer la recherche avec la requête
            $books = $bookManager->searchBooks($query);
        } else {
            // Récupérer tous les livres si aucune recherche n'est effectuée
            $books = $bookManager->getAllBooks();
        }

        // Passer les livres à la vue
        $view = new View("Nos Livres");
        $view->render("books-list", ['books' => $books, 'query' => $query]);
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
    public function showApropos()
    {
        $view = new View("A propos");
        $view->render("apropos");
    }
}
