<section class="intro-container">
    <img src="./img/hero.png" alt="" srcset="">
    <h2 class="title">Rejoignez nos lecteurs passionnés</h2>
    <p class="intro-text">
        Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
    </p>
</section>

<!-- <div class="bookList">
    <?php if (!empty($books)) { ?>
        <?php foreach ($books as $book) { ?>
            <article class="article">
                <h2><?= htmlspecialchars($book->getTitle()) ?></h2>
                <span class="quotation">«</span>
                <p><?= htmlspecialchars($book->getContent(400)) ?></p>

                <div class="footer">
                    <span class="info"><?= ucfirst(Utils::convertDateToFrenchFormat($book->getDateCreation())) ?></span>
                    <a class="info" href="index.php?action=showBook&id=<?= $book->getId() ?>">Lire +</a>
                </div>
            </article>
        <?php } ?>
    <?php } else { ?>
        <p>Aucun livre disponible pour le moment.</p>
    <?php } ?>
</div> -->
