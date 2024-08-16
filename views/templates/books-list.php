<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="search-wrapper">
    <h1>Nos Livres à l'échange</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="action" value="books">
        <div class="search-container">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" name="query" placeholder="Rechercher un livre ou un auteur...">
        </div>
    </form>
</div>

<section class="book-list">
    <div class="books">
        <?php if (!empty($books)) { ?>
            <?php foreach ($books as $book) { ?>
                <?php include 'includes/book-card.php'; ?>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun livre disponible pour le moment.</p>
        <?php } ?>
    </div>
</section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Le DOM est chargé');
        const searchInput = document.querySelector('input[name="query"]');
        const bookCards = document.querySelectorAll('.book-card'); // Sélection de toutes les cartes

        // Fonction pour gérer la recherche
        function handleSearch() {
            const query = searchInput.value.toLowerCase();
            console.log('Événement input déclenché, requête:', query); // Affiche la requête entrée par l'utilisateur

            // Filtrage local des cartes
            bookCards.forEach(function(card) {
                const title = card.dataset.title.toLowerCase();
                const author = card.dataset.author.toLowerCase();

                if (title.includes(query) || author.includes(query)) {
                    card.style.display = 'block'; // Affiche la carte
                } else {
                    card.style.display = 'none'; // Cache la carte
                }
            });

            // Si la requête est suffisamment longue, envoi de la requête AJAX
            if (query.length >= 2) {
                console.log('La requête est suffisamment longue, envoi de la requête AJAX'); // Vérifie si la longueur de la requête est suffisante

                fetch(`index.php?action=books&query=${encodeURIComponent(query)}&ajax=true`)
                    .then(response => {
                        console.log('Requête AJAX réussie, statut de la réponse:', response.status); // Affiche le statut de la réponse
                        return response.text();
                    })
                    .then(data => {
                        console.log('Réponse reçue, mise à jour de la liste des livres'); // Confirme que les données sont reçues
                        booksContainer.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des livres:', error); // Affiche toute erreur qui se produit pendant l'appel AJAX
                    });
            }
        }

        // Ajoute un gestionnaire d'événements sur l'input de recherche
        if (searchInput) {
            console.log('Champ de recherche trouvé'); // Vérifie que le champ de recherche est trouvé
            searchInput.addEventListener('input', handleSearch);
        } else {
            console.error('Champ de recherche non trouvé'); // Signale que le champ de recherche n'a pas été trouvé
        }

        // Fonction pour gérer les clics sur les cartes de livres
        function handleCardClick(event) {
            const card = event.target.closest('.book-card');
            if (card) {
                const bookId = card.dataset.bookId;
                console.log('Carte de livre cliquée, ID du livre:', bookId);

                fetch(`index.php?action=book-detail&id=${bookId}&ajax=true`)
                    .then(response => {
                        console.log('Requête AJAX pour les détails du livre réussie, statut de la réponse:', response.status); // Affiche le statut de la réponse
                        return response.text();
                    })
                    .then(data => {
                        console.log('Réponse reçue pour les détails du livre'); // Confirme que les données sont reçues
                        // Vous pouvez manipuler les détails du livre ici
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des détails du livre:', error); // Affiche toute erreur qui se produit pendant l'appel AJAX
                    });
            }
        }

        // Ajoute un gestionnaire d'événements sur les cartes de livres
        document.querySelector('.book-list').addEventListener('click', handleCardClick);
    });
</script>