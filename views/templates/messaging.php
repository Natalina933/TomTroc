<?php if (isset($_GET['error'])) : ?>
    <div class="error-message">
        <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="messaging-container">
    <div class="messenger-container">
        <!-- Section 1 : Liste des conversations -->
        <section class="sidebar" aria-label="Liste des conversations">
            <h1>Messagerie</h1>
            <div class="conversation-container">
                <ul class="conversations" role="list">
                    <?php if (empty($lastMessages)) : ?>
                        <li>Aucune conversation trouvée.</li>
                    <?php else : ?>
                        <?php foreach ($lastMessages as $message) : ?>
                            <?php
                            $otherUser = $message->getSender()->getId() != $_SESSION['user']['id'] ? $message->getSender() : $message->getReceiver();
                            ?>
                            <div class="conversation-wrapper">
                                <li class="conversation <?= isset($activeConversation) && $activeConversation['receiver']['id'] == $otherUser->getId() ? 'active' : '' ?>">
                                    <a href="index.php?action=showMessaging&receiver_id=<?= htmlspecialchars($otherUser->getId()) ?>">
                                        <div class="conversation-info">
                                            <img src="<?= htmlspecialchars($otherUser->getProfilePicture() ?? 'assets/img/users/default-profile.png') ?>" alt="Photo de profil de <?= htmlspecialchars($otherUser->getUsername()) ?>" class="profile-picture">
                                            <p class="name"><?= htmlspecialchars($otherUser->getUsername()) ?></p>
                                            <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()->format('H:i')) ?></span>
                                        </div>
                                        <span class="description"><?= htmlspecialchars(substr($message->getContent(), 0, 30)) ?>...</span>
                                    </a>
                                </li>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </section>

        <!-- Section 2 : Conversation active ou message par défaut -->
        <section class="chat" aria-label="Conversation active">
            <?php if (isset($activeConversation)) : ?>
                <div class="chat-header">
                    <div class="chat-user-info">
                        <img src="<?= htmlspecialchars($activeConversation['receiver']['profilePicture'] ?? 'assets/img/users/default-profile.png') ?>" alt="Photo de profil" class="profile-picture">
                        <span class="chat-title"><?= htmlspecialchars($activeConversation['receiver']['username']) ?></span>
                    </div>
                </div>

                <div class="messages-container" role="log" aria-live="polite">
                    <div class="messages">
                        <?php foreach ($activeConversation['messages'] as $message) : ?>
                            <div class="message <?= $message->getSender()->getId() == $_SESSION['user']['id'] ? 'sent' : 'received' ?>">
                                <?php if ($message->getSender()->getId() != $_SESSION['user']['id']) : ?>
                                    <img src="<?= htmlspecialchars($message->getSender()->getProfilePicture() ?? 'assets/img/users/default-profile.png') ?>" alt="Photo de profil" class="profile-picture">
                                <?php endif; ?>
                                <div class="message-content">
                                    <?= htmlspecialchars($message->getContent()) ?>
                                </div>
                                <div class="message-footer">
                                    <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()->format('d/m/Y H:i')) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="chat-input">
                    <form action="index.php?action=sendMessage" method="post" class="message-form" aria-label="Envoyer un message">
                        <label for="message-content" class="visually-hidden">Votre message</label>
                        <textarea id="message-content" name="content" placeholder="Votre message..." class="message-textarea"></textarea>
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($activeConversation['receiver']['id']) ?>">
                        <button type="submit" class="btn send-button">Envoyer</button>
                    </form>
                </div>
            <?php else : ?>
                <div class="no-conversation">
                    <p>Sélectionnez une conversation pour commencer à chatter.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
    </main>