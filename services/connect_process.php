<?php

// Récupération des données du formulaire
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Instance de UserManager
$userManager = new UserManager();

// Vérification de l'utilisateur
$user = $userManager->getUserByUsername($username);

if ($user && password_verify($password, $user->getPassword())) {
    // Connexion réussie, redirection vers la page myaccount
    header('Location: myaccount.php');
    exit();
} else {
    // Identifiant ou mot de passe incorrect, redirection vers la page d'inscription
    header('Location: registrationForm.php');
    exit();
}
