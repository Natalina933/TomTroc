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
                <?php if (empty($messages)) : ?>
                    <li>Aucune conversation trouvée.</li>
                <?php else : ?>
                    <!-- Boucle pour afficher chaque message reçu -->
                    <?php foreach ($messages as $message) : ?>
                        <?php
                        $sender = $message->getSender()->getId() != $_SESSION['user']['id'] ? $message->getSender() : $message->getReceiver();
                        ?>
                        <li class="conversation" data-message-id="<?= htmlspecialchars($message->getId(), ENT_QUOTES, 'UTF-8') ?>" data-receiver-id="<?= htmlspecialchars($sender->getId(), ENT_QUOTES, 'UTF-8') ?>">
                            <a href="index.php?action=showMessaging&receiver_id=<?= htmlspecialchars($sender->getId(), ENT_QUOTES, 'UTF-8') ?>">
                                <img src="<?= htmlspecialchars($sender->getProfilePicture() ?? 'default-profile.png', ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil">
                                <div class="conversation-info">
                                    <p class="name"><?= htmlspecialchars($sender->getUsername(), ENT_QUOTES, 'UTF-8') ?></p>
                                    <span class="description"><?= htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()->format('H:i'), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>

        <!-- Section 2 : Chat -->
        <?php if (isset($conversation) && !empty($conversation)) : ?>
            <div class="chat">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <?php
                        $chatUser = $conversation[0]->getSender()->getId() != $_SESSION['user']['id']
                            ? $conversation[0]->getSender()
                            : $conversation[0]->getReceiver();
                        ?>
                        <img src="<?= htmlspecialchars($chatUser->getProfilePicture() ?? 'default-profile.png', ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($chatUser->getUsername(), ENT_QUOTES, 'UTF-8') ?>">
                        <span class="chat-title"><?= htmlspecialchars($chatUser->getUsername(), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>

                <!-- Section des messages -->
                <div class="messages-container">
                    <div class="messages">
                        <?php foreach ($conversation as $message) : ?>
                            <div class="message <?= $message->getSender()->getId() == $_SESSION['user']['id'] ? 'sent' : 'received' ?>">
                                <?php if ($message->getSender()->getId() != $_SESSION['user']['id']) : ?>
                                    <img src="<?= htmlspecialchars($message->getSender()->getProfilePicture() ?? 'default-profile.png', ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($message->getSender()->getUsername(), ENT_QUOTES, 'UTF-8') ?>">
                                <?php endif; ?>
                                <div class="message-content">
                                    <?= htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div class="message-footer">
                                    <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Champ de texte pour envoyer un message -->
                <div class="chat-input">
                    <form action="index.php?action=sendMessage" method="post">
                        <textarea name="content" placeholder="Votre message..." required></textarea>
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($chatUser->getId(), ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit">Envoyer</button>
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