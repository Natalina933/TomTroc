<?php
require_once 'config/config.php';
require_once 'config/autoloader.php';

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
            $articleController = new ArticleController();
            $articleController->showHome();
            break;

        // case 'apropos':
        //     $articleController = new ArticleController();
        //     $articleController->showApropos();
        //     break;
        //     /**
        //      * Affichage d'un article spécifique.
        //      * Récupère l'ID de l'article depuis la requête, instancie le contrôleur de l'article,
        //      * et appelle la méthode showArticle avec l'ID pour afficher les détails de l'article.
        //      */
        // case 'showArticle':
        //     $idArticle = Utils::request('id');
        //     error_log("Action: showArticle, ID: $idArticle");
        //     $articleController = new ArticleController();
        //     $articleController->showArticle(true);
        //     break;

        // case 'addArticle':
        //     $articleController = new ArticleController();
        //     $articleController->addArticle();
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

        // case 'showUpdateArticleForm':
        //     $adminController = new AdminController();
        //     $adminController->showUpdateArticleForm();
        //     break;

        // case 'updateArticle':
        //     $adminController = new AdminController();
        //     $adminController->updateArticle();
        //     break;

        // case 'deleteArticle':
        //     $adminController = new AdminController();
        //     $adminController->deleteArticle();
        //     break;
        //     //création du controller articles
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
