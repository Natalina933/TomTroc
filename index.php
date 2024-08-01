<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

// On récupère l'action demandée par l'utilisateur.
// Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');
error_log("Action requested: " . $action);
// Try catch global pour gérer les erreurs
try {
    // Pour chaque action, on appelle le bon contrôleur et la bonne méthode.
    switch ($action) {
            // Pages accessibles à tous.
        case 'home':
            $bookController = new bookController();
            $bookController->showHome();
            break;

            // case 'apropos':
            //     $bookController = new bookController();
            //     $bookController->showApropos();
            //     break;
            //     /**
            //      * Affichage d'un book spécifique.
            //      * Récupère l'ID de l'book depuis la requête, instancie le contrôleur de l'book,
            //      * et appelle la méthode showbook avec l'ID pour afficher les détails de l'book.
            //      */
            // case 'showbook':
            //     $idbook = Utils::request('id');
            //     error_log("Action: showbook, ID: $idbook");
            //     $bookController = new bookController();
            //     $bookController->showbook(true);
            //     break;

            // case 'addbook':
            //     $bookController = new bookController();
            //     $bookController->addbook();
            //     break;

            // case 'addComment':
            //     $commentController = new CommentController();
            //     $commentController->addComment();
            //     break;


            //     // Section admin & connexion. 
            // case 'admin':
            //     $adminController = new AdminController();
            //     $adminController->showAdmin();
            //     break;

            // case 'connectionForm':
            //     $adminController = new AdminController();
            //     $adminController->displayConnectionForm();
            //     break;

            // case 'connectUser':
            //     $adminController = new AdminController();
            //     $adminController->connectUser();
            //     break;

            // case 'disconnectUser':
            //     $adminController = new AdminController();
            //     $adminController->disconnectUser();
            //     break;

            // case 'showUpdatebookForm':
            //     $adminController = new AdminController();
            //     $adminController->showUpdatebookForm();
            //     break;

            // case 'updatebook':
            //     $adminController = new AdminController();
            //     $adminController->updatebook();
            //     break;

            // case 'deletebook':
            //     $adminController = new AdminController();
            //     $adminController->deletebook();
            //     break;
            //     //création du controller books
            // case 'showMonitoring':
            //     $monitoringController = new MonitoringController();
            //     $monitoringController->showMonitoring();
            //     break;
            //     //création du controller delete commentaire
            // case 'deleteComment':
            //     $monitoringController = new MonitoringController();
            //     $monitoringController->deleteComment();


            break;


        default:
            throw new Exception("La page demandée n'existe pas.");
    }
} catch (Exception $e) {
    // En cas d'erreur, on affiche la page d'erreur.
    error_log("Exception caught: " . $e->getMessage());
    $errorView = new View('Erreur');
    $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}
