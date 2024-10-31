<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>


<div class="account-container">
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

            </div>
            <p>
                <?= ($user['username']) ?></p>
            <?php
            // Créer un objet DateTime à partir de la date d'inscription
            $createdAt = new DateTime($user['createdAt']);
            $now = new DateTime();
            // Calculer la différence
            $diff = $createdAt->diff($now);

            $memberSince = '';
            if ($diff->y > 0) {
                $memberSince .= $diff->y . ' ' . ($diff->y > 1 ? 'ans' : 'an');
            }
            if ($diff->m > 0) {
                if (!empty($memberSince)) $memberSince .= ' et ';
                $memberSince .= $diff->m . ' ' . ($diff->m > 1 ? 'mois' : 'mois');
            }
            if ($diff->y === 0 && $diff->m === 0) {
                $memberSince = '1 mois';
            }
            ?>
            <p>Membre depuis : <?= ($memberSince) ?></p>
            <p>BIBLIOTHÈQUE</p>
            <div class="library-info">
                <img src="/assets/img/icon_books.svg" alt="Icône de livres">
                <!-- Affichage du nombre de livres -->
                <p>Nombre total de livres : <?= ($totalBooks) ?></p>
            </div>

        </div>
    </div>

    <!-- Carré 2 : Informations personnelles -->
    <div class="account-card">
        <form>
            <label for="username">Pseudo</label>
            <input type="text" id="username" name="username" value="<?= ($user['username']) ?>" disabled required>
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
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">Aucun livre trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>