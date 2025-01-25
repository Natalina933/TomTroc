
    // document.addEventListener('DOMContentLoaded', function() {
    //     console.log('Le DOM est chargé');
    //     const searchInput = document.querySelector('input[name="query"]');
    //     const bookCards = document.querySelectorAll('.book-card'); // Sélection de toutes les cartes

    //     // Fonction pour gérer la recherche
    //     function handleSearch() {
    //         const query = searchInput.value.toLowerCase();
    //         console.log('Événement input déclenché, requête:', query); // Affiche la requête entrée par l'utilisateur

    //         // Filtrage local des cartes
    //         bookCards.forEach(function(card) {
    //             const title = card.dataset.title.toLowerCase();
    //             const author = card.dataset.author.toLowerCase();

    //             if (title.includes(query) || author.includes(query)) {
    //                 card.style.display = 'block'; // Affiche la carte
    //             } else {
    //                 card.style.display = 'none'; // Cache la carte
    //             }
    //         });

    //         // Si la requête est suffisamment longue, envoi de la requête AJAX
    //         if (query.length >= 2) {
    //             console.log('La requête est suffisamment longue, envoi de la requête AJAX'); // Vérifie si la longueur de la requête est suffisante

    //             fetch(`index.php?action=books&query=${encodeURIComponent(query)}&ajax=true`)
    //                 .then(response => {
    //                     console.log('Requête AJAX réussie, statut de la réponse:', response.status); // Affiche le statut de la réponse
    //                     return response.text();
    //                 })
    //                 .then(data => {
    //                     console.log('Réponse reçue, mise à jour de la liste des livres'); // Confirme que les données sont reçues
    //                     booksContainer.innerHTML = data;
    //                 })
    //                 .catch(error => {
    //                     console.error('Erreur lors de la récupération des livres:', error); // Affiche toute erreur qui se produit pendant l'appel AJAX
    //                 });
    //         }
    //     }

    //     // Ajoute un gestionnaire d'événements sur l'input de recherche
    //     if (searchInput) {
    //         console.log('Champ de recherche trouvé'); // Vérifie que le champ de recherche est trouvé
    //         searchInput.addEventListener('input', handleSearch);
    //     } else {
    //         console.error('Champ de recherche non trouvé'); // Signale que le champ de recherche n'a pas été trouvé
    //     }

    //     // Fonction pour gérer les clics sur les cartes de livres
    //     function handleCardClick(event) {
    //         const card = event.target.closest('.book-card');
    //         if (card) {
    //             const bookId = card.dataset.bookId;
    //             window.location.href = `index.php?action=book-detail&id=${bookId}`;
    //         }
    //     }

    //     document.querySelector('.books').addEventListener('click', handleCardClick);

    // });
