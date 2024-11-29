<div class="book-card" data-book-id="<?= htmlspecialchars($book->getId()) ?>" data-title="<?= htmlspecialchars($book->getTitle()) ?>" data-author="<?= htmlspecialchars($book->getAuthor()) ?>">
    <a href="index.php?action=book-detail&id=<?= htmlspecialchars($book->getId()) ?>">
        <div class="image-container">
            <?php
            $imgSrc = $book->getImg() ?? '';
            if (!empty($imgSrc) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $imgSrc)) {
            ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Image de <?= htmlspecialchars($book->getTitle()) ?>">
            <?php } else { ?>
                <p>Image non disponible</p>
            <?php } ?>
    </a>

    <?php if (!$book->isAvailable()) { ?>
        <div class="availability-badge">Non dispo.</div>
    <?php } ?>
</div>

<div class="text-book-card">
    <h3><?= htmlspecialchars($book->getTitle()) ?? 'Titre non disponible' ?></h3>
    <p class="author"><?= htmlspecialchars($book->getAuthor()) ?? 'Auteur non disponible' ?></p>
    <!-- Vérification si l'utilisateur (vendeur) est bien défini -->
    <?php if ($book->getUser()) { ?>
        <p class="seller">
            Vendu par :
            <a href="index.php?action=showPublicAccount&user_id=<?= htmlspecialchars($book->getUser()->getId()) ?>">
                <?= htmlspecialchars($book->getUser()->getUsername()) ?>
            </a>
        </p>
    <?php } else { ?>
        <p class="seller">Vendeur inconnu</p>
    <?php } ?>
</div>
</div>