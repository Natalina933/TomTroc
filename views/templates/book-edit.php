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
            <img src="<?php echo $book->getImg(); ?>" alt="Image du livre" class="book-image">
            <div class="image-upload">
                <label for="img" class="upload-label">Modifier la photo</label>
                <input type="file" id="img" name="img" class="file-input">
            </div>
        </div>

        <div class="book-form-section">

            <form action="index.php?action=<?= $isEditing ? 'editbook&id=' . $book->getId() : 'addBook' ?>" method="post" enctype="multipart/form-data" class="edit-form">
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
                        <option value="0" <?= $isEditing && !$book->isAvailable() ? 'selected' : '' ?>>Non disponible</option>
                    </select>
                </div>

                <button type="submit" class="submit-button"><?= $isEditing ? 'Modifier' : 'Ajouter' ?></button>
            </form>
        </div>
    </div>
</div>