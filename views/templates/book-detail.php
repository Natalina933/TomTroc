<div <div class="book-detail">
    <?php if ($book) { ?>
        <?php if ($book->getImg()) { ?>
            <img src="<?= htmlspecialchars($book->getImg()) ?>" alt="Image de <?= htmlspecialchars($book->getTitle()) ?>" class="book-image">
        <?php } else { ?>
            <p>Image non disponible</p>
        <?php } ?>
        <h1 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h1>
        <p class="book-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
        <p class="book-description">Description: <?= nl2br(htmlspecialchars($book->getDescription())) ?></p>
        <p class="book-owner-title">Propriétaire</p>
        
        <div class="owner-info">
            <?php if ($owner && $owner->getProfilePicture()) { ?>
                <img src="<?= htmlspecialchars($owner->getProfilePicture()) ?>" alt="Photo de <?= htmlspecialchars($owner->getFirstName() . ' ' . $owner->getLastName()) ?>" class="owner-photo">
            <?php } else { ?>
                <div class="owner-photo placeholder"></div>
            <?php } ?>
            <p class="owner-name"><?= htmlspecialchars($owner ? $owner->getFirstName() . ' ' . $owner->getLastName() : 'Propriétaire inconnu') ?></p>
        </div>
        
        <button class="message-button">Envoyer un message</button>
    <?php } else { ?>
        <p>Livre non trouvé.</p>
    <?php } ?>
</div>
