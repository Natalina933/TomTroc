<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>


<div class="account-container">
    <h1>Mon Compte</h1>

    <!-- Section 1 : Informations du compte -->
    <div class="account-sections">
        <!-- Carré 1 : Profile -->
        <div class="account-card">
            <div class="account-profile">
                <!-- Vérification et affichage de la photo de profil -->
                <?php if (($user['profilePicture']) && !empty($user['profilePicture'])) : ?>
                    <img src="<?= $user['profilePicture'] ?>" alt="Photo de profil">
                <?php else : ?>
                    <img src="/assets/img/users/profile-default.svg" alt="Photo par défaut">
                <?php endif; ?>

                <!-- Formulaire pour modifier l'image de profil -->
                <form id="profilePictureForm" action="index.php?action=updateProfilePicture" method="post" enctype="multipart/form-data">
                    <input type="file" id="profilePictureInput" name="profilePicture" accept="image/*" style="display:none;">
                    <button type="button" id="changePictureButton">Modifier</button>
                    <input type="submit" id="submitForm" style="display:none;">
                </form>
            </div>
            <p><?= ($user['username']) ?></p>
            <p>Membre depuis : <?= ($user['createdAt']) ?></p>
            <p>BIBLIOTHÈQUE</p>
            <div class="library-info">
                <img src="/assets/img/icon_books.svg" alt="Icône de livres">
            </div>
        </div>

        <!-- Carré 2 : Informations personnelles -->
        <div class="account-card">
            <h2>Vos informations personnelles</h2>
            <form action="index.php?action=editUser" method="post">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= ($user['email']) ?>" required>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••••••" disabled>

                <label for="username">Pseudo</label>
                <input type="text" id="username" name="username" value="<?= ($user['username']) ?>" disabled required>

                <button type="button" id="editButton">Modifier</button>
                <button type="submit" id="submitButton" style="display:none;">Enregistrer</button>
            </form>
        </div>
    </div>
</div>

<!-- Section 3 : Tableau des livres -->
<h2>Vos livres</h2>
<table class="table-books">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Description</th>
            <th>Disponible</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($books)) : ?>
            <?php foreach ($books as $book) : ?>
                <tr>
                    <td><img src="<?= ($book->getImg()) ?>" alt="Photo du livre" width="50"></td>
                    <td><?= ($book->getTitle()) ?></td>
                    <td><?= ($book->getAuthor()) ?></td>
                    <td><?= ($book->getDescription()) ?></td>
                    <td><?= ($book->isAvailable() ? 'Oui' : 'Non') ?></td>
                    <td>
                        <a href="index.php?action=editBook&id=<?= (int)$book->getId() ?>">Editer</a> |
                        <a href="index.php?action=deleteBook&id=<?= (int)$book->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">Aucun livre trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Script pour gérer la sélection et la prévisualisation de l'image -->
<script>
    document.getElementById('changePictureButton').onclick = function() {
        document.getElementById('profilePictureInput').click();
    };

    document.getElementById('profilePictureInput').addEventListener('change', function() {
        document.getElementById('profilePictureForm').submit();
    });

    document.getElementById('editButton').onclick = function() {
        document.getElementById('email').disabled = false;
        document.getElementById('password').disabled = false;
        document.getElementById('username').disabled = false;

        document.getElementById('editButton').style.display = 'none';
        document.getElementById('submitButton').style.display = 'inline';
    };
</script>