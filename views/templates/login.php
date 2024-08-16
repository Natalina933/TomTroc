<body>
    <div class="login-wrapper">
        <h1>Inscription</h1>
        <form method="post" action="login_process.php"> <!-- Assurez-vous que 'login_process.php' existe et gère la soumission du formulaire -->
            <div class="input-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p class="register-link">
            Pas encore inscrit ? <a href="register.php">Inscrivez-vous</a>
        </p>
        <div class="home-image">
            <img src="path/to/your/homepage-image.jpg" alt="Image d'accueil"> <!-- Remplacez 'path/to/your/homepage-image.jpg' par le chemin réel de l'image utilisée dans la page d'accueil -->
        </div>
    </div>
</body>