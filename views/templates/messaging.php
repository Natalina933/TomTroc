<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>

<body>
    <main class="container">
        <!-- Section 1 : sidebar -->
        <div class="sidebar">
            <h2>Messagerie</h2>

            <ul class="conversations">
                <!-- Boucle pour afficher chaque message reçu -->
                <?php foreach ($messages as $message) : ?>
                    <li class="conversation" data-message-id="<?= $message->getId() ?>" data-receiver-id="<?= $message->getReceiverId() ?>">

                        <!-- Vérification et affichage de la photo de profil de l'expéditeur -->
                        <?php $sender = $message->getSender(); ?>
                        <?php if (!empty($sender['profilePicture'])) : ?>
                            <img src="<?= $sender['profilePicture'] ?>" alt="Photo de profil">
                        <?php else : ?>
                            <img src="/assets/img/users/profile-default.svg" alt="Photo par défaut">
                        <?php endif; ?>

                        <!-- Affichage du nom de l'expéditeur -->
                        <div class="conversation-info">
                            <p class="name"><?= htmlspecialchars($sender['username'], ENT_QUOTES, 'UTF-8') ?></p>

                            <!-- Affichage de l'extrait du message avec '...' si le message est trop long -->
                            <span class="description">
                                <?= (strlen($message->getContent()) > 50) ? substr($message->getContent(), 0, 50) . '...' : htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <!-- Affichage de l'heure d'envoi du message -->
                            <span class="timestamp"><?= date('H:i', strtotime($message->getCreatedAt())) ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Section 2 : chat -->
        <section class="chat">
            <div class="chat-header">
                <img src="profil1.jpg" alt="Photo de profil" class="sender">
                <img src="profil2.jpg" alt="Photo de profil" class="recipient">
                <span class="chat-title">Discussion avec John Doe</span>
            </div>
            <div class="messages">
                <div class="message">
                    <div class="message-header">
                        <span class="author">John Doe</span>
                        <span class="timestamp">12:30</span>
                    </div>
                    <div class="message-content">
                        Lorem ipsum dolor sit amet...
                    </div>
                </div>
            </div>
            <div class="chat-input">
                <textarea placeholder="Votre message..."></textarea>
                <button>Envoyer</button>
            </div>
            </div>
    </main>
    <script src="script.js"></script>
</body>