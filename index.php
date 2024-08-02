<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

// On récupère l'action demandée par l'utilisateur. Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');
error_log("Action requested: " . $action);

// Try catch global pour gérer les erreurs
try {
    // Pour chaque action, on appelle le bon contrôleur et la bonne méthode.
    switch ($action) {
        // Pages accessibles à tous.
        case 'home':
            $bookController = new BookController();
            $bookController->showHome();
            break;

        case 'addbook':
            $bookController = new BookController();
            $bookController->addBook();
            break;

        // case 'addComment':
        //     $commentController = new CommentController();
        //     $commentController->addComment();
        //     break;

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

        // case 'showUpdateBookForm':
        //     $adminController = new AdminController();
        //     $adminController->showUpdateBookForm();
        //     break;

        // case 'updateBook':
        //     $adminController = new AdminController();
        //     $adminController->updateBook();
        //     break;

        // case 'deleteBook':
        //     $adminController = new AdminController();
        //     $adminController->deleteBook();
        //     break;

        // case 'showMonitoring':
        //     $monitoringController = new MonitoringController();
        //     $monitoringController->showMonitoring();
        //     break;

        // case 'deleteComment':
        //     $monitoringController = new MonitoringController();
        //     $monitoringController->deleteComment();
        //     break;

        default:
            throw new Exception("La page demandée n'existe pas.");
    }
} catch (Exception $e) {
    // En cas d'erreur, on affiche la page d'erreur.
    error_log("Exception caught: " . $e->getMessage());
    $errorView = new View('Erreur');
    $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}
?>
