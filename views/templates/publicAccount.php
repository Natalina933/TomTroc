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
                <?php if ($user->getProfilePicture() && !empty($user->getProfilePicture())) : ?>
                    <img src="<?= htmlspecialchars($user->getProfilePicture(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil">
                <?php else : ?>
                    <img src="/assets/img/users/profile-default.svg" alt="Photo par défaut">
                <?php endif; ?>
            </div>
            <p><?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?></p>
            <?php
            // Créer un objet DateTime à partir de la date d'inscription
            $createdAt = new DateTime($user->getCreatedAt());
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
            <p>Membre depuis : <?= htmlspecialchars($memberSince, ENT_QUOTES, 'UTF-8') ?></p>
            <p>BIBLIOTHÈQUE</p>
            <div class="library-info">
                <img src="/assets/img/icon_books.svg" alt="Icône de livres">
                <!-- Affichage du nombre de livres -->
                <?= htmlspecialchars($totalBooks, ENT_QUOTES, 'UTF-8') ?> livres
            </div>
            <a href="index.php?action=showMessaging&receiver_id=<?= htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8') ?>" class="btn">Envoyer un message</a>
            </div>
    </div>



    <!-- Section 3 : Tableau des livres -->

    <table class="table-books">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)) : ?>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($book->getImg(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo du livre" width="50"></td>
                        <td><?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($book->getAuthor(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($book->getDescription(), ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">Aucun livre trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>