<?php
$pageTitle = "Ajouter un nouveau livre";
$backUrl = "index.php?action=myAccount";
?>

<div class="book-edit-container">
    <a href="<?= $backUrl ?>" class="back-button">Retour</a>
    <h2 class="edit-title"><?= $pageTitle ?></h2>

    <div class="book-edit-content">
        <div class="book-image-section">
            <img id="bookImagePreview" src="/assets/img/defaultBook.webp" alt="Photo du livre" class="image-preview">
            <div class="image-upload">
                <label for="img" class="upload-label">Ajouter une photo</label>
                <input type="file" id="img" name="img" class="file-input" onchange="previewBookImage(event)">
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
    function previewBookImage(event) {
        const input = event.target;
        const preview = document.getElementById('bookImagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            // Afficher l'image par défaut si aucun fichier n'est sélectionné
            preview.src = "/assets/img/defaultBook.webp";
        }
    }
</script>