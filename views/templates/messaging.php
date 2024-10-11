<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>

<main class="messaging-container">
    <div class="messenger-container">
        <!-- Section 1 : Conversations -->
        <section class="sidebar">
            <h2>Messagerie</h2>
            <ul class="conversations">
                <!-- Vérification si des messages existent -->
                <?php if (empty($messages)) : ?>
                    <li>Aucune conversation trouvée.</li>
                <?php else : ?>
                    <!-- Boucle pour afficher chaque message reçu -->
                    <?php foreach ($messages as $message) : ?>
                        <li class="conversation" data-message-id="<?= htmlspecialchars($message['message_id'], ENT_QUOTES, 'UTF-8') ?>" data-receiver-id="<?= htmlspecialchars($message['sender']['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <img src="<?= htmlspecialchars($message['sender']['profilePicture'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($message['sender']['username'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="conversation-info">
                                <p class="name"><?= htmlspecialchars($message['sender']['username'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="description"><?= htmlspecialchars($message['content'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="timestamp"><?= htmlspecialchars(date('H:i', strtotime($message['createdAt'])), ENT_QUOTES, 'UTF-8') ?></span> <!-- Correction ici -->
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>

        <!-- Section 2 : Chat -->
        <section class="chat">
            <div class="chat-header">
                <!-- <img src="profil1.jpg" alt="Photo de profil" class="sender"> -->
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
        </section>
    </div>
</main>