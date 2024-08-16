<body>
    <div class="registration-wrapper">
        <h1>Inscription</h1>
        <div class="registration-form">
            <form action="register_process.php" method="post" class="foldedCorner">
                <div class="formGrid">
                    <label for="username">Pseudo</label>
                    <input type="text" name="username" id="username" required>

                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>

                    <button class="submit">S'inscrire</button>
                </div>
            </form>
        </div>
        <p class="login-link">
            Déjà inscrit ? <a href="login.php">Connectez-vous</a>
        </p>
        <div class="home-image">
            <img src="../../assets/img/hero.png" alt="Image d'accueil"> <!-- Remplacez 'path/to/your/homepage-image.jpg' par le chemin réel de l'image utilisée dans la page d'accueil -->
        </div>
    </div>
</body>