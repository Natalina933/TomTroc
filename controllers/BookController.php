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
        $books = $bookManager->getAllbooks();

        $view = new View("Accueil");
        $view->render("home", ['books' => $books]);
    }


    /**
     * Affiche le détail d'un book.
     * @return void
     */
    public function showbook($shouldIncrementViews = false): void
    {
        $id = Utils::request("id", -1);
        error_log("showbook called with ID: $id, shouldIncrementViews: " . ($shouldIncrementViews ? 'true' : 'false'));

        $bookManager = new bookManager();
        $book = $bookManager->getbookById($id, $shouldIncrementViews);

        // $commentManager = new CommentManager();
        // $comments = $commentManager->getAllCommentsBybookId($id);

        if (!$book) {
            throw new Exception("Le livre demandé n'existe pas. ID: $id");
        }

        $view = new View($book->getTitle());
        $view->render("detailbook", ['book' => $book]);
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
