<?php

?>



<div class="account-container">
    <h1>Mon Compte</h1>

    <!-- Section 1 : Informations du compte -->
    <div class="account-sections">
        <!-- Carré 1 : Bibliothèque -->
        <div class="account-card">
            <img src="path_to_user_image" alt="Photo de profil">
            <button>Modifier</button>
            <p><?= ($user['username']) ?></p>
            <p>Membre depuis : <?= ($user['id']) ?></p>
            <p>BIBLIOTHÈQUE</p>
            <div style="display: flex; justify-content: center; align-items: center;">
                <img src="icon_books.png" alt="Icone de livres" style="margin-right: 10px;">
                <p><?= ($user['role']) ?> livres</p>
            </div>
        </div>

        <!-- Carré 2 : Informations personnelles -->
        <div class="account-card">
            <h2>Vos informations personnelles</h2>
            <form action="index.php?action=updateUser" method="post">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= 'email' ?>" required>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password">
                <label for="username">Pseudo</label>
                <input type="text" id="username" name="username" value="<?= $user['username'] ?>" required>
                <button type="submit">Enregistrer</button>
            </form>
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
            <?php foreach ($user->getBooks() as $book) : ?>
                <tr>
                    /**Modifier tous comme le title */
                    <td><img src="<?= htmlspecialchars($book->getImage()) ?>" alt="Photo du livre"></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                    <td><?= htmlspecialchars($book->getDescription()) ?></td>
                    <td><?= htmlspecialchars($book->isAvailable() ? 'Oui' : 'Non') ?></td>
                    <td>
                        <a href="index.php?action=editBook&id=<?= htmlspecialchars($book->getId()) ?>">Editer</a> |
                        <a href="index.php?action=deleteBook&id=<?= htmlspecialchars($book->getId()) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>