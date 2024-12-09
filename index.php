<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

// On récupère l'action demandée par l'utilisateur. Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');
$bookId = Utils::request('id', 0); // Récupérer l'ID du livre depuis la requête

error_log("Action demandée : " . $action . ", ID du livre : " . $bookId);

try {
    // Instancier les contrôleurs
    $bookController = new BookController();
    $userController = new UserController();
    $messageController = new MessageController();
    $publicAccountController = new PublicAccountController();

    switch ($action) {
            // ****Pages accessibles à tous****
        case 'home':
            $bookController->showHome();
            break;

            // ****Gestion des livres****
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

        case 'editbook':
            if ($bookId > 0) {
                $bookController->editBook($bookId);
            } else {
                throw new Exception("ID du livre invalide.");
            }
            break;

        case 'addBook':
            $bookController->addBook();
            break;

        case 'deletebook':
            if ($bookId > 0) {
                $bookController->deleteBook($bookId);
            } else {
                throw new Exception("ID du livre invalide.");
            }
            break;
        case 'displayAddBookForm':
            $userController->displayAddBookForm();
            break;
            // ****Gestion des utilisateurs****
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
            $userController->displayRegistrationForm();
            break;

        case 'bookCount':
            $bookController->displayBooksSection();
            break;

            case 'updateProfilePicture':
                $userController->updateProfilePicture();
                break;

        case 'editUser':
            $userController->editUser();
            break;

            // **** Gestion de la messagerie ****
        case 'showMessaging':
            $messageController->showMessaging();
            break;

        case 'showMessagesList':
            $messageController->showMessagesList();
            break;

        case 'showSentMessages':
            $messageController->showSentMessages();
            break;

        case 'sendMessage':
            $messageController->sendMessage();
            break;

            // **** Affichage du compte public ****
        case 'showPublicAccount':
            $publicAccountController->showPublicAccount();
            break;


        default:
            throw new Exception("La page demandée n'existe pas.");
    }
} catch (Exception $e) {
    // En cas d'erreur, on affiche la page d'erreur.
    error_log("Exception capturée : " . $e->getMessage());
    $errorView = new View('Erreur');
    $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}
