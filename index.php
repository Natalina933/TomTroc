<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

// On récupère l'action demandée par l'utilisateur. Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');
$bookId = Utils::request('id', 0); // Récupérer l'ID du livre depuis la requête
error_log("Action requested: " . $action . ", Book ID: " . $bookId);

try {
    // Instancier les contrôleurs
    $bookController = new BookController();
    $userController = new userController();
    // Pour chaque action, on appelle le bon contrôleur et la bonne méthode.
    switch ($action) {
            // Pages accessibles à tous.
        case 'home':
            $bookController->showHome();
            break;

        case 'books':
            $bookController->showBooksList();
            break;

        case 'book-detail':
            if ($bookId > 0) {
                $bookController->showBookDetail($bookId);
            } else {
                throw new Exception("ID du livre invalide.");
            }
            break;

        case 'addbook':
            $bookController->addBook();
            break;

        case 'registerUser':
            $userController->registerUser();
            break;

        case 'connectUser':
            $userController->connectUser();
            break;

        case 'myAccount':
            $userController->showMyAccount();
            break;


        case 'disconnectUser':
            $userController->disconnectUser();
            break;

        case 'connectionForm':
            $userController->displayConnectionForm();
            break;
            
        case 'registrationForm':
            $userController = new UserController();
            $userController->displayRegistrationForm();
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
