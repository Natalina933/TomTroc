<div class="connection-wrapper">
    <h1>Connexion</h1>
    <div class="connection-form">
        <form action="index.php?action=connectUser" method="POST" class="foldedCorner">
            <div class="formGrid">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>

                <button class="submit">Se connecter</button>
            </div>
        </form>
    </div>
    <p class="login-link">
        Pas de compte ? <a href="index.php?action=registrationForm">Inscrivez-vous</a>
    </p>
    <div class="home-image">
        <img src="../../assets/img/hero.webp" alt="Image d'accueil">
    </div>
</div>