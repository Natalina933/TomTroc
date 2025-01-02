<?php

/**
 * Ce template affiche les détails d'un livre, y compris une option pour envoyer un message au propriétaire.
 */
?>
<a href="index.php?action=books" class="book-link">Nos livres > <?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></a>
<div class="book-detail">
    <?php if ($book) : ?>
        <article class="book-layout">
            <div class="book-image-container">
                <?php if ($book->getImg()) : ?>
                    <img src="<?= htmlspecialchars($book->getImg(), ENT_QUOTES, 'UTF-8') ?>" alt="Image de <?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>" class="book-image">
                <?php else : ?>
                    <p>Image non disponible</p>
                <?php endif; ?>
            </div>
            <div class="book-content">
                <h1 class="book-title"><?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="book-author">par <?= htmlspecialchars($book->getAuthor(), ENT_QUOTES, 'UTF-8') ?></p>

                <div class="book-description-container">
                    <p class="book-description-title">DESCRIPTION</p>
                    <p class="book-description-content"><?= nl2br(htmlspecialchars($book->getDescription())) ?></p>
                </div>

                <!-- Informations sur le propriétaire du livre -->
                <p class="owner-title">PROPRIÉTAIRE</p>

                <?php if ($book->getUser()) : ?>
                    <?php $user = $book->getUser(); ?>

                    <!-- Lien vers la page de compte public du propriétaire -->
                    <a href="index.php?action=showPublicAccount&user_id=<?= htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8') ?>" class="owner-info">

                        <!-- Affichage de la photo de profil -->
                        <?php if ($user->getProfilePicture()) : ?>
                            <img src="<?= htmlspecialchars($user->getProfilePicture(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?>" class="owner-photo">
                        <?php else : ?>
                            <div class="owner-photo placeholder"></div>
                        <?php endif; ?>

                        <!-- Affichage du nom d'utilisateur -->
                        <p class="owner-name"><?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?></p>
                    </a>

                    <!-- Bouton pour envoyer un message -->
                    <button href="index.php?action=showMessaging&receiver_id=<?= htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8') ?>" class="btn">Envoyer un message</button>

                <?php else : ?>
                    <p>Propriétaire inconnu</p>
                <?php endif; ?>
            </div>
        </article>
    <?php else : ?>
        <p>Livre non trouvé.</p>
    <?php endif; ?>
</div>