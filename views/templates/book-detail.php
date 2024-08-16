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
                <img src="<?= htmlspecialchars($book->getImg()) ?>" alt="Image de <?= htmlspecialchars($book->getTitle()) ?>" class="book-image">
            <?php } else { ?>
                <p>Image non disponible</p>
            <?php } ?>

            <!-- Affichage des détails du livre -->
            <h1 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h1>
            <p class="book-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
            <p class="book-description">Description: <?= nl2br(htmlspecialchars($book->getDescription())) ?></p>

            <!-- Informations sur le propriétaire du livre -->
            <div class="owner-info">
                <p class="book-owner-title">Propriétaire</p>
                <?php if ($user) { ?>
                    <!-- Affichage de la photo de profil du propriétaire -->
                    <?php if ($user->getProfilePicture()) { ?>
                        <img src="<?= htmlspecialchars($user->getProfilePicture()) ?>" alt="Photo de <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?>" class="owner-photo">
                    <?php } else { ?>
                        <div class="owner-photo placeholder"></div>
                    <?php } ?>
                    <p class="owner-name">
                        <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?>
                    </p>
                    <p class="owner-contact-info">
                        <span>Email: <?= htmlspecialchars($user->getEmail()) ?></span><br>
                        <?php if ($user->getPhoneNumber()) { ?>
                            <span>Téléphone: <?= htmlspecialchars($user->getPhoneNumber()) ?></span>
                        <?php } ?>
                    </p>
                <?php } else { ?>
                    <p>Propriétaire inconnu</p>
                <?php } ?>
            </div>

            <!-- Formulaire pour envoyer un message au propriétaire -->
            <form action="index.php" method="post" class="message-form">
                <input type="hidden" name="action" value="sendMessage">
                <input type="hidden" name="bookId" value="<?= $book->getId() ?>">
                <input type="hidden" name="ownerId" value="<?= $user ? $user->getId() : '' ?>">

                <h2>Envoyer un message</h2>
                <label for="message">Message</label>
                <textarea name="message" id="message" required></textarea>

                <button class="submit">Envoyer</button>
            </form>
        </article>
    <?php } else { ?>
        <p>Livre non trouvé.</p>
    <?php } ?>
</div>