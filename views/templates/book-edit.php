<!-- <-bouton retour -->
<?php
$isEditing = isset($book) && $book->getId() > 0;
$pageTitle = $isEditing ? "Modifier les informations" : "Ajouter un nouveau livre";
$backUrl = $isEditing ? "index.php?action=book-detail&id=" . $book->getId() : "index.php?action=myAccount";
?>

<div class="book-edit-container">
    <a href="<?= $backUrl ?>" class="back-button">Retour</a>
    <h2 class="edit-title"><?= $pageTitle ?></h2>

    <div class="book-edit-content">
        <div class="book-image-section">
            <?php if (isset($book) && $book->getImg() && !empty($book->getImg())) : ?>
                <img id="bookImage" src="<?= htmlspecialchars($book->getImg(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo du livre <?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>">
            <?php else : ?>
                <img id="bookImage" src="/assets/img/defaultBook.webp" alt="Image par défaut du livre">
            <?php endif; ?>

            <form action="index.php?action=updateBookImage&id=<?= htmlspecialchars($book->getId(), ENT_QUOTES) ?>" method="post" enctype="multipart/form-data">
                <input type="file" id="bookImageInput" name="img" accept="image/*" style="display:none;">
                <div class="btn-book-image">
                    <a type="button" id="changeBookImageButton" class="book-image-link">Modifier la photo</a>
                </div>
                <input type="submit" id="submitBookImageForm" style="display:none;">
            </form>
        </div>

        <div class="book-form-section">
            <form id="bookForm" action="index.php?action=<?= $isEditing ? 'editBook&id=' . $book->getId() : 'addBook' ?>" method="post" enctype="multipart/form-data" class="edit-form" onsubmit="return confirmSubmission();">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="id" value="<?= $book->getId(); ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" id="title" name="title" value="<?= $isEditing ? htmlspecialchars($book->getTitle()) : '' ?>" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="author" class="form-label">Auteur</label>
                    <input type="text" id="author" name="author" value="<?= $isEditing ? htmlspecialchars($book->getAuthor()) : '' ?>" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Commentaire</label>
                    <textarea id="description" name="description" required class="form-textarea"><?= $isEditing ? htmlspecialchars($book->getDescription()) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="available" class="form-label">Disponibilité</label>
                    <select id="available" name="available" class="form-select">
                        <option value="1" <?= $isEditing && $book->isAvailable() ? 'selected' : '' ?>>Disponible</option>
                        <option value="0" <?= $isEditing && !$book->isAvailable() ? 'selected' : '' ?>>Non dispo.</option>
                    </select>
                </div>

                <button type="submit" class="submit-button"><?= $isEditing ? 'Modifier' : 'Ajouter' ?></button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmSubmission() {
        const title = document.getElementById('title').value;
        const author = document.getElementById('author').value;
        const description = document.getElementById('description').value;
        const available = document.getElementById('available').value;

        const imgInput = document.getElementById('bookImageInput');
        let imagePath = document.getElementById('bookImage').getAttribute('src');

        // Si une nouvelle image a été sélectionnée, utilisez son nom
        if (imgInput.files && imgInput.files[0]) {
            imagePath = '/assets/img/books/' + imgInput.files[0].name;
        }

        const message = `Vous êtes sur le point d'enregistrer les informations suivantes :\n\n` +
            `Titre: ${title}\n` +
            `Auteur: ${author}\n` +
            `Photo: ${imagePath}\n` +
            `Description: ${description}\n` +
            `Disponibilité: ${available === '1' ? 'Disponible' : 'Non dispo.'}\n\n` +
            `Confirmez-vous l'enregistrement ?`;

        return confirm(message);
    }

    // Prévisualisation de l'image
    function previewImage(input) {
        const bookImage = document.getElementById('bookImage');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bookImage.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Gestionnaire pour afficher le sélecteur de fichier
    document.getElementById('changeBookImageButton').addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du clic
        document.getElementById('bookImageInput').click();
    });

    // Prévisualisation et soumission automatique après sélection d'une image
    document.getElementById('bookImageInput').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('bookImage').src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            // Soumet le formulaire pour mettre à jour l'image
            document.getElementById('submitBookImageForm').click();
        }
    });
</script>