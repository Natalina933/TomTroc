<header>
    <nav class="main-nav">
        <div class="nav-wrapper">
            <a href="index.php" class="logo-link">
                <img src="./img/logo_tom_troc.png" alt="logo_tom_troc" class="logo-image">
            </a>
            <div class="navigation">
                <div class="nav-group nav-group--left">
                    <a href="#home" class="nav-link">Accueil</a>
                    <a href="#books" class="nav-link">Nos livres</a>
                </div>
                <div class="nav-group nav-group--right">
                    <a href="#messages" class="nav-link">Messagerie</a>
                    <a href="#account" class="nav-link">Mon compte</a>
                    <?php
                    // Si on est connecté, on affiche le bouton de déconnexion, sinon, on affiche le bouton de connexion :
                    if (isset($_SESSION['user'])) {
                        echo '<a href="index.php?action=disconnectUser" class="nav-link">Déconnexion</a>';
                    } else {
                        echo '<a href="index.php?action=login" class="nav-link">Connexion</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <h1 class="site-title">Tom Troc</h1>
</header>