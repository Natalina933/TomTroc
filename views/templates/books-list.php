<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="search-wrapper">
    <h1>Nos Livres à l'échange</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="action" value="books">
        <div class="search-container">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" name="query" placeholder="Rechercher un livre ou un auteur...">
        </div>
    </form>
</div>

<section class="book-list">
    <div class="books">
        <?php if (!empty($books)) { ?>
            <?php foreach ($books as $book) { ?>
                <div class="book-card">
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
            <?php } ?>
        <?php } else { ?>
            <p>Aucun livre disponible pour le moment.</p>
        <?php } ?>

    </div>
</section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="query"]');
        const booksContainer = document.querySelector('.book-list .books');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = searchInput.value;

                if (query.length >= 2) {
                    fetch(`index.php?action=books&query=${encodeURIComponent(query)}&ajax=true`)
                        .then(response => response.text())
                        .then(data => {
                            booksContainer.innerHTML = data;
                        })
                        .catch(error => console.error('Error fetching books:', error));
                } else {
                    // Afficher un message d'absence de livres si la requête est trop courte
                    booksContainer.innerHTML = '<p>Aucun livre disponible pour le moment.</p>';
                }
            });
        }
    });
</script>