
<div class="book-card" data-book-id="<?= $book->getId() ?>" data-title="<?= htmlspecialchars($book->getTitle()) ?>" data-author="<?= htmlspecialchars($book->getAuthor()) ?>">
    <div class="image-container">
        <a href="index.php?action=book-detail&id=<?= $book->getId() ?>">
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
        <h3><?= htmlspecialchars($book->getTitle() ?? 'Titre non disponible') ?></h3>
        <p class="author"><?= htmlspecialchars($book->getAuthor() ?? 'Auteur non disponible') ?></p>
        <p class="seller">Vendu par : <?= htmlspecialchars($book->getUserId() ?? 'Utilisateur inconnu') ?></p>
    </div>
</div>
