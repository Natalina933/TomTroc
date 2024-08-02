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
    <?php include 'header.php'; ?>

    <main>
        <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>