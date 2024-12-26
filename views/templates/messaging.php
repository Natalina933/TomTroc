<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<main class="messaging-container">
    <div class="messenger-container">
        <!-- Section 1 : Conversations -->
        <section class="sidebar">
            <h2>Messagerie</h2>
            <ul class="conversations">
                <?php if (empty($lastMessages)) : ?>
                    <li>Aucune conversation trouvée.</li>
                <?php else : ?>
                    <?php foreach ($lastMessages as $message) : ?>
                        <?php
                        $sender = $message->getSender()->getId() != $_SESSION['user']['id'] ? $message->getSender() : $message->getReceiver();
                        ?>
                        <li class="conversation" data-message-id="<?= htmlspecialchars($message->getId()) ?>" data-receiver-id="<?= htmlspecialchars($sender->getId()) ?>">
                            <a href="index.php?action=showMessaging&receiver_id=<?= htmlspecialchars($sender->getId()) ?>">
                                <img src="<?= htmlspecialchars($sender->getProfilePicture() ?? 'assets/img/users/default-profile.png') ?>" alt="Photo de profil" class="profile-picture">
                                <div class="conversation-info">
                                    <p class="name"><?= htmlspecialchars($sender->getUsername()) ?></p>
                                    <span class="description"><?= htmlspecialchars(substr($message->getContent(), 0, 30)) ?>...</span>
                                    <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()->format('H:i')) ?></span>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>

        <!-- Section 2 : Chat -->
        <?php if (isset($receiver)) : ?>
            <div class="chat">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <img src="<?= htmlspecialchars($receiver['profilePicture'] ?? 'assets/img/users/default-profile.png') ?>" alt="Photo de profil de <?= htmlspecialchars($receiver['username']) ?>" class="profile-picture">
                        <span class="chat-title"><?= htmlspecialchars($receiver['username']) ?></span>
                    </div>
                </div>

                <div class="messages-container">
                    <div class="messages">
                        <?php if (!empty($conversation)) : ?>
                            <?php foreach ($conversation as $message) : ?>
                                <!-- ... (affichage des messages existants) ... -->
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="no-messages">Aucun message. Commencez la conversation !</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chat-input">
                    <form action="index.php?action=sendMessage" method="post" class="message-form">
                        <textarea name="content" placeholder="Votre message..." class="message-textarea"></textarea>
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiver['id']) ?>">
                        <button type="submit" class="btn send-button">Envoyer</button>
                    </form>
                </div>
            </div>
        <?php else : ?>
            <div class="no-conversation">
                <p>Sélectionnez une conversation pour commencer à chatter.</p>
            </div>
        <?php endif; ?>
    </div>
</main>