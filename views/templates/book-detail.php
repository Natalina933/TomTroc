<?php

/**
 * Ce template affiche les détails d'un livre, y compris une option pour envoyer un message au propriétaire.
 */
?>
<div class="book-detail">
    <?php if ($book) { ?>
        <article class="mainArticle">
            <!-- Affichage de l'image du livre -->
            <?php if ($book->getImg()) { ?>
                <img src="<?= htmlspecialchars($book->getImg(), ENT_QUOTES, 'UTF-8') ?>" alt="Image de <?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>" class="book-image">
            <?php } else { ?>
                <p>Image non disponible</p>
            <?php } ?>

            <!-- Affichage des détails du livre -->
            <h1 class="book-title"><?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="book-author">par <?= htmlspecialchars($book->getAuthor(), ENT_QUOTES, 'UTF-8') ?></p>
            <div class="book-description-container">
                <p class="book-description-title">Description</p>
                <p class="book-description-content"><?= nl2br(htmlspecialchars($book->getDescription())) ?></p>
            </div>
            <!-- Informations sur le propriétaire du livre -->

            <!-- Titre de la section -->
            <p class="owner-title">PROPRIÉTAIRE</p>
            <div class="owner-info">

                <!-- Informations du propriétaire -->
                <?php if ($user) { ?>
                    <!-- Affichage de la photo de profil -->
                    <?php if ($user->getProfilePicture()) { ?>
                        <img src="<?= ($user->getProfilePicture()) ?>" alt="Photo de profil de <?= htmlspecialchars($user->getUsername()) ?>" class="owner-photo">
                    <?php } else { ?>
                        <div class="owner-photo placeholder"></div>
                    <?php } ?>

                    <!-- Affichage du nom d'utilisateur -->
                    <p class="owner-name"><?= htmlspecialchars($user->getUsername()) ?></p>
                <?php } else { ?>
                    <p>Propriétaire inconnu</p>
                <?php } ?>
            </div>


            <a href="message" class="btn">Envoyer un message</a>
            </form>
        </article>
    <?php } else { ?>
        <p>Livre non trouvé.</p>
    <?php } ?>
</div>