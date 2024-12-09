<?php
$pageTitle = "Ajouter un nouveau livre";
$backUrl = "index.php?action=myAccount";
?>

<div class="book-edit-container">
    <a href="<?= $backUrl ?>" class="back-button">Retour</a>
    <h2 class="edit-title"><?= $pageTitle ?></h2>

    <div class="book-edit-content">
        <div class="book-image-section">
            <img src="/assets/img/defaultBook.png" alt="Photo du livre">
            <div class="image-upload">
                <label for="img" class="upload-label">Ajouter une photo</label>
                <input type="file" id="img" name="img" class="file-input">
            </div>
        </div>

        <div class="book-form-section">
            <form id="bookForm" action="index.php?action=addBook" method="post" enctype="multipart/form-data" class="edit-form" onsubmit="return confirmSubmission();">
                <div class="form-group">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" id="title" name="title" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="author" class="form-label">Auteur</label>
                    <input type="text" id="author" name="author" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Commentaire</label>
                    <textarea id="description" name="description" required class="form-textarea"></textarea>
                </div>

                <div class="form-group">
                    <label for="available" class="form-label">Disponibilité</label>
                    <select id="available" name="available" class="form-select">
                        <option value="1" selected>Disponible</option>
                        <option value="0">Non disponible</option>
                    </select>
                </div>

                <button type="submit" class="submit-button">Ajouter</button>
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

        const message = `Vous êtes sur le point d'enregistrer les informations suivantes :\n\n` +
            `Titre: ${title}\n` +
            `Auteur: ${author}\n` +
            `Description: ${description}\n` +
            `Disponibilité: ${available === '1' ? 'Disponible' : 'Non disponible'}\n\n` +
            `Confirmez-vous l'enregistrement ?`;

        return confirm(message);
    }
</script>