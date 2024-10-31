<?php

class UserController
{


    public function showPublicAccount(): void
    {
        $userController = new UserController();
        $userController->showPublicAccount();
        // Affichage du template pour la page de mon compte public
        $view = new View("Mon compte public");
        $view->render("myPublicAccount");
    }
}
