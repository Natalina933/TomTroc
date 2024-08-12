<h1>Nos Livres à l'échange</h1>
<form method="get" action="index.php">
    <input type="hidden" name="action" value="books">
    <div class="search-container">
        <input type="text" name="query" placeholder="Rechercher un livre ou un auteur...">
        <button type="submit"><img src="/img/loupe.png" alt="Rechercher"></button>
    </div>
</form>
<section class="book-list">
    <h2 class="section-title">Les derniers livres ajoutés</h2>
    <div class="books">
        <?php if (!empty($books)) { ?>
            <?php foreach ($books as $book) { ?>
                <div class="book-card">
                    <?php
                    $imgSrc = $book->getImg() ?? '';
                    // Si l'image est définie et accessible
                    if (!empty($imgSrc) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $imgSrc)) {
                    ?>
                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Image de <?= htmlspecialchars($book->getTitle()) ?>">
                    <?php } else { ?>
                        <p>Image non disponible</p>
                    <?php } ?>
                    <h3><?= htmlspecialchars($book->getTitle() ?? 'Titre non disponible') ?></h3>
                    <p class="author"><?= htmlspecialchars($book->getAuthor() ?? 'Auteur non disponible') ?></p>
                    <p class="seller">Vendu par : <?= htmlspecialchars($book->getUserId() ?? 'Utilisateur inconnu') ?></p>
                    <a href="index.php?action=showBook&id=<?= $book->getId(); ?>">Voir Détails</a>

                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun livre disponible pour le moment.</p>
        <?php } ?>
        <button class="btn">Voir tous les livres</button>
    </div>
</section>
</main>