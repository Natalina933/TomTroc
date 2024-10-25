<?php
?>
<h2>Modifier le livre</h2>
<form action="index.php?action=editbook&id=<?= $book->getId(); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $book->getId(); ?>">

    <label for="title">Titre</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($book->getTitle()); ?>" required>

    <label for="author">Auteur</label>
    <input type="text" id="author" name="author" value="<?= htmlspecialchars($book->getAuthor()); ?>" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($book->getDescription()); ?></textarea>

    <label for="img">Image :</label>
    <input type="file" id="img" name="img">

    <label for="available">Disponible</label>
    <input type="checkbox" id="available" name="available" <?= $book->isAvailable() ? 'checked' : ''; ?>>

    <button type="submit">Enregistrer les modifications</button>
</form>