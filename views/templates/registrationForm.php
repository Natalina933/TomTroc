<div class="registration-wrapper">
    <h1>Inscription</h1>
    <div class="registration-form">
        <form action="index.php?action=registerUser" method="post" class="foldedCorner">
            <div class="formGrid">
                <label for="username">Pseudo</label>
                <input type="text" name="username" id="username" autocomplete="false" required>

                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" autocomplete="false" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" autocomplete="false" required>

                <button class="submit">S'inscrire</button>
            </div>
        </form>
    </div>
    <p class="login-link">
        Déjà inscrit ? <a href="index.php?action=connectionForm">Connectez-vous</a>
    </p>
    <div class="home-image">
        <img src="../../assets/img/hero.webp" alt="Image d'accueil">
    </div>
</div>