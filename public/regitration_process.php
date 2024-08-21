<?php

// Récupération des données du formulaire
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Instance de UserManager
$userManager = new UserManager();

// Hashage du mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$createdAt = date('Y-m-d H:i:s');

// Création d'un nouvel utilisateur
$user = new User([
    'username' => $username,
    'email' => $email,
    'password' => $hashedPassword,
    'created_at' => $createdAt,
]);

// Enregistrement de l'utilisateur
if ($userManager->registerUser($user)) {
    // Inscription réussie, redirection vers la page de connexion
    header('Location: connectionForm.php');
    exit();
} else {
    // Erreur lors de l'inscription, afficher un message d'erreur
    echo "Erreur lors de l'inscription. Veuillez réessayer.";
}
