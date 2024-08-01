<?php

/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.  
 * 
 * Les variables qui doivent impérativement être définies sont : 
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page. 
 */

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="index.php?action=books">Nos livres à l'échange</a>
            <a href="index.php?action=messages">Messagerie</a>
            <a href="index.php?action=profile">Mon compte</a>
            <?php
            // Si on est connecté, on affiche le bouton de déconnexion, sinon, on affiche le bouton de connexion : 
            if (isset($_SESSION['user'])) {
                echo '<a href="index.php?action=disconnectUser">Déconnexion</a>';
            } else {
                echo '<a href="index.php?action=login">Connexion</a>';
            }
            ?>
        </nav>
        <h1>Tom Troc</h1>
    </header>

    <main>
        <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
    </main>

    <footer>
        <a href="index.php?action=privacy">Politique de confidentialité</a>
        <a href="index.php?action=legal">Mentions légales</a>
        <a href="index.php">Tom Troc©</a>
        <img src="./img/logo_tom_troc.png" alt="logo_tom_troc">
    </footer>

</body>

</html>