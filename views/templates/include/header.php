<header>
    <nav class="main-nav">
        <div class="nav-wrapper">
            <a href="index.php" class="logo-link">
                <img src="../../../assets/img/logo_tom_troc.png" alt="logo_tom_troc" class="logo-image">
            </a>
            <button class="hamburger" aria-label="Toggle navigation" aria-expanded="false" aria-controls="menu">
                &#9776;
            </button>
            <div class="menu">
                <div class="nav-group nav-group--left">
                    <a href="index.php?action=home" class="nav-link">Accueil</a>
                    <a href="index.php?action=books" class="nav-link">Nos livres à l'échange</a>
                </div>
                <div class="nav-group nav-group--right">
                    <?php
                    $unreadCount = isset($_SESSION['unreadCount']) ? $_SESSION['unreadCount'] : 0;
                    ?>
                    <a href="index.php?action=showMessaging" class="nav-link messagerie-link">
                        <!-- Icône de bulle -->
                        <img src="../../../assets/img/icons/bubble-icon.png" alt="Bulle" class="bubble-icon">
                        Messagerie
                        <?php if ($unreadCount > 0) : ?>
                            <span class="unread-bubble"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="index.php?action=myAccount" class="nav-link">
                        <!-- Icône de compte -->
                        <img src="../../../assets/img/icons/account-icon.png" alt="Compte" class="account-icon">
                        Mon compte</a>
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="index.php?action=disconnectUser" class="nav-link">
                            Déconnexion</a>
                    <?php } else { ?>
                        <a href="index.php?action=connectionForm" class="nav-link">Connexion</a>
                    <?php } ?>
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
                const isActive = menu.classList.toggle('active');
                hamburger.classList.toggle('is-active');
                hamburger.setAttribute('aria-expanded', isActive);
            });

            const navLinks = menu.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    menu.classList.remove('active');
                    hamburger.classList.remove('is-active');
                    hamburger.setAttribute('aria-expanded', 'false');
                });
            });
        }
    });
</script>