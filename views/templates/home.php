<section class="intro-container">
    <img src="../../assets/img/hero.png" alt="Image représentant un livre et des lecteurs">
    <div class="intro-wrapper">
        <h2 class="title">Rejoignez nos lecteurs passionnés</h2>
        <p class="intro-text">
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>
        <a href="index.php?action=books" class="btn" alt = "Découvrir nos livres">Découvrir</a>
    </div>
</section>

<section class="book-list">
    <h2 class="section-title">Les derniers livres ajoutés</h2>
    <div class="books">
        <?php if (!empty($books)) { ?>
            <?php foreach ($books as $book) { ?>
                <?php include 'include/book-card.php'; ?>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun livre disponible pour le moment.</p>
        <?php } ?>
    </div>
    <button class="btn">Voir tous les livres</button>

</section>

<section class="how-it-works">
    <h2 class="section-title">Comment ça marche ?</h2>
    <p class="intro-text">
        Échanger des livres avec TomTroc c’est simple et amusant !
    </p>
    <div class="steps-wrapper">
        <div class="step-box">
            <p>Inscrivez-vous gratuitement sur notre plateforme.</p>
        </div>
        <div class="step-box">
            <p>Ajoutez les livres que vous souhaitez échanger à votre profil.</p>
        </div>
        <div class="step-box">
            <p>Parcourez les livres disponibles chez d'autres membres.</p>
        </div>
        <div class="step-box">
            <p>Proposez un échange et discutez avec d'autres passionnés de lecture.</p>
        </div>
    </div>
    <button class="btn view-all-books">Voir tous les livres</button>
</section>

<section class="values-section">
    <div class="banner-image">
        <img src="../../assets/img/banner.png" alt="Bandeau représentant les valeurs de TomTroc">
    </div>
    <div class="values-content">
        <h2 class="values-title">Nos valeurs</h2>
        <p class="values-paragraph">
            Chez TomTroc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.
        </p>
        <p class="values-paragraph">
            Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.
        </p>
        <p class="values-paragraph">
            Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.
        </p>
        <div class="values-footer">
            <div class="values-signature">
                L’équipe TomTroc.
            </div>
            <img class="vector-image" src="/assets/img/Vector_2.svg" alt="Décoration Vector 2">
        </div>
    </div>
</section>