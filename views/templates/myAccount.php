<?php if (!empty($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
<main class="account-wrapper">
    <h1 class="account-title">Mon Compte</h1>
    <div class="account-container">
        <!-- Carré 1 : Profil -->
        <section class="account-card profile-card" aria-label="Informations du profil">
            <div class="account-profile">
                <?php if (!empty($user['profilePicture'])) : ?>
                    <img src="<?= htmlspecialchars($user['profilePicture'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>">
                <?php else : ?>
                    <img src="/assets/img/users/profile-default.svg" alt="Photo par défaut">
                <?php endif; ?>

                <form id="profilePictureForm" action="index.php?action=updateProfilePicture" method="post" enctype="multipart/form-data">
                    <input type="file" id="profilePictureInput" name="profilePicture" accept="image/*" style="display:none;">
                    <button type="button" id="changePictureButton">Modifier</button>
                    <input type="submit" id="submitForm" style="display:none;">
                </form>
            </div>
            <p><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p>
            <p>Membre depuis :
                <?php
                if (isset($user['createdAt']) && isset($dateFormatter)) {
                    $createdAtDateTime = new DateTime($user['createdAt']);
                    echo htmlspecialchars($dateFormatter->formatMemberSince($createdAtDateTime), ENT_QUOTES, 'UTF-8');
                } else {
                    echo 'Date inconnue';
                }
                ?>
            </p>

            <p>BIBLIOTHÈQUE</p>
            <div class="library-info">
                <img src="/assets/img/icon_books.svg" alt="Icône de livres">
                <p>Nombre total de livres : <?= (int)$totalBooks ?></p>
            </div>
        </section>

        <!-- Carré 2 : Informations personnelles -->
        <section class="account-card info-card" aria-label="Informations personnelles">
            <h2>Vos informations personnelles</h2>
            <form action="index.php?action=editUser" method="post">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" disabled required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••••••" disabled>

                <label for="username">Pseudo</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" disabled required>

                <button type="button" id="editButton">Modifier</button>
                <button type="submit" id="submitButton" style="display:none;">Enregistrer</button>
            </form>
        </section>
    </div>
</main>

<!-- Section 3 : Tableau des livres -->
<section class="books-section" aria-label="Bibliothèque de l'utilisateur">
    <h2>Vos livres</h2>
    <a href="index.php?action=displayAddBookForm" class="btn" aria-label="Ajouter un nouveau livre à votre bibliothèque">Ajouter un livre</a>
    <table class="table-books" aria-label="Liste de vos livres">
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
                        <td><img src="<?= htmlspecialchars($book->getImg() ?: '/assets/img/book-default.svg', ENT_QUOTES, 'UTF-8') ?>" alt="Photo du livre"></td>
                        <td><?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($book->getAuthor(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($book->getDescription(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $book->isAvailable() ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="index.php?action=editbook&id=<?= (int)$book->getId() ?>">Éditer</a>
                            <a href="index.php?action=deleteBook&id=<?= (int)$book->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun livre trouvé.</td>
                    <td>
                        <a href="index.php?action=addBook" class="btn btn-primary">Ajouter un livre</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>


<script defer>
    document.getElementById('changePictureButton').addEventListener('click', () => {
        document.getElementById('profilePictureInput').click();
    });

    document.getElementById('profilePictureInput').addEventListener('change', () => {
        document.getElementById('profilePictureForm').submit();
    });

    document.getElementById('editButton').addEventListener('click', () => {
        ['email', 'password', 'username'].forEach(id => {
            document.getElementById(id).disabled = false;
        });

        document.getElementById('editButton').style.display = 'none';
        document.getElementById('submitButton').style.display = 'inline';
    });
</script>