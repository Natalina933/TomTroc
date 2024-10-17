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
        <div class="chat">
            <div class="chat-header">
                <span class="chat-title">Discussion avec <?= htmlspecialchars($conversation[0]['sender']['username'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="messages">
                <?php foreach ($conversation as $message) : ?>
                    <div class="message <?= $message['sender']['id'] == $_SESSION['user']['id'] ? 'sent' : 'received' ?>">
                        <div class="message-header">
                            <span class="author"><?= htmlspecialchars($message['sender']['username'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="timestamp"><?= htmlspecialchars(date('H:i', strtotime($message['createdAt'])), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="message-content">
                            <?= htmlspecialchars($message['content'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- <div class="chat-input">
                <form action="sendMessage.php" method="post">
                    <textarea name="content" placeholder="Votre message..."></textarea>
                    <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">
                    <button type="submit">Envoyer</button>
                </form>
            </div> -->
        </div>


    </div>
</main>