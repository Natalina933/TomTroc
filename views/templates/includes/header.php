<header>
    <nav class="main-nav">
        <div class="nav-wrapper">
            <a href="index.php" class="logo-link">
                <img src="./img/logo_tom_troc.png" alt="logo_tom_troc" class="logo-image">
            </a>
            <button class="hamburger" aria-label="Toggle navigation">
                &#9776;
            </button>
            <div class="menu">
                <div class="nav-group nav-group--left">
                    <a href="#home" class="nav-link">Accueil</a>
                    <a href="#books" class="nav-link">Nos livres</a>
                </div>
                <div class="nav-group nav-group--right">
                    <a href="#messages" class="nav-link">Messagerie</a>
                    <a href="#account" class="nav-link">Mon compte</a>
                    <?php
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
</header>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.querySelector('.hamburger');
        const menu = document.querySelector('.menu');

        if (hamburger && menu) {
            hamburger.addEventListener('click', function() {
                menu.classList.toggle('active');
                hamburger.classList.toggle('is-active');
            });

            const navLinks = menu.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    menu.classList.remove('active');
                    hamburger.classList.remove('is-active');
                });
            });
        }
    });
</script>