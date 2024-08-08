<section class="intro-container">
    <img src="./img/hero.png" alt="" srcset="">
    <div class="intro-wrapper">
        <h2 class="title">Rejoignez nos lecteurs passionnés</h2>
            <p class="intro-text">
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>
        <button class="btn">Découvrir</button>
    </div>
</section>

<section class="book-list">
    <h2 class="section-title">Les derniers livres ajoutés</h2>
    <div class="books">
        <?php if (!empty($books)) { ?>
            <?php foreach ($books as $book) { ?>
                <div class="book-card">
                    <h3><?= htmlspecialchars($book->getTitle()) ?></h3>
                    <p><strong>Auteur:</strong> <?= htmlspecialchars($book->getAuthor()) ?></p>
                    <?php if ($book->getImg()) { ?>
                        <img src="<?= htmlspecialchars($book->getImge()) ?>" alt="Image de <?= htmlspecialchars($book->getTitle()) ?>">
                    <?php } ?>
                    <p><?= htmlspecialchars($book->getDescription(400)) ?></p>
                    <p><strong>Disponible:</strong> <?= $book->isAvailable() ? 'Oui' : 'Non' ?></p>
                    <p><strong>Date d'ajout:</strong> <?= htmlspecialchars($book->getCreatedAt()->format('Y-m-d H:i:s')) ?></p>
                    <a class="info" href="index.php?action=showBook&id=<?= $book->getId() ?>">Lire +</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun livre disponible pour le moment.</p>
        <?php } ?>
    </div>

</section>