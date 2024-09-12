<?php

/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.  
 * 
 * Les variables qui doivent impérativement être définies sont : 
 *      $title string : le titre de la page.
 *      $description string : le contenu de la page. 
 */

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" description="width=device-width, initial-scale=1.0">
    <title><?= ($title) ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/Mini_logo.svg">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

    <main>
        <?= $description /* Ici est affiché le contenu réel de la page. */ ?>
    </main>

    <?php include 'include/footer.php'; ?>
</body>

</html>