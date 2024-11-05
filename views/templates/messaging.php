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
            <a class="conversations">
                <?php if (count($messages) == 0) : ?>
                    <li>Aucune conversation trouvée.</li>
                <?php else : ?>
                    <!-- Boucle pour afficher chaque message reçu -->
                    <?php foreach ($messages as $message) : ?>
                        <?php $sender = $message->getSender()->getId() != $_SESSION['user']['id'] ?  $message->getSender() :  $message->getReceiver(); ?>
                        <a href="index.php?action=showMessaging&receiver_id=<?php echo $sender->getId(); ?>">
                            <li class="conversation" data-message-id="<?= htmlspecialchars($message->getId(), ENT_QUOTES, 'UTF-8') ?>" data-receiver-id="<?= htmlspecialchars($message->getSender()->getId(), ENT_QUOTES, 'UTF-8') ?>">
                                <img src="<?= htmlspecialchars($sender->getProfilePicture(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($sender->getUsername(), ENT_QUOTES, 'UTF-8') ?>">
                                <div class="conversation-info">
                                    <p class="name"><?= htmlspecialchars($sender->getUsername(), ENT_QUOTES, 'UTF-8') ?></p>
                                    <span class="description"><?= htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="timestamp"><?= htmlspecialchars(date('H:i', $message->getCreatedAt()->getTimeStamp()), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </li>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                </ul>
        </section>

        <!-- Section 2 : Chat -->
        <?php if (isset($conversation)) { ?>
            <div class="chat">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <!-- Photo de profil et Nom de l'utilisateur avec qui vous discutez -->
                        <?php
                        if (!empty($conversation)) {
                            $chatUser = $message->getSender()->getId() != $_SESSION['user']['id'] ?  $message->getSender() :  $message->getReceiver();;
                        } else {
                            echo "Aucun message dans cette conversation.";
                        }
                        ?>

                        <img src="<?= htmlspecialchars($chatUser->getProfilePicture(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($chatUser->getUsername(), ENT_QUOTES, 'UTF-8') ?>">
                        <span class="chat-title"><?= htmlspecialchars($chatUser->getUsername(), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>

                <!-- Section des messages -->
                <div class="messages-container">
                    <div class="messages">
                        <?php foreach ($conversation as $message) : ?>
                            <!-- Message envoyé -->
                            <?php if ($message->getSender()->getId() != $_SESSION['user']['id']) : ?>
                                <div class="message sent">
                                    <div class="message-content">
                                        <?= htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <div class="message-footer">
                                        <span class="timestamp"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($message->getCreatedAt()->getTimeStamp())), ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                </div>
                            <?php else : ?>
                                <!-- Message reçu -->
                                <div class="message received">
                                    <img src="<?= htmlspecialchars($message->getSender()->getProfilePicture(), ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil de <?= htmlspecialchars($message->getSender()->getUsername(), ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="message-content">
                                        <?= htmlspecialchars($message->getContent(), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <div class="message-footer">
                                        <span class="timestamp"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($message->getCreatedAt()->getTimeStamp())), ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Champ de texte pour envoyer un message -->
                <div class="chat-input">
                    <form action="index.php?action=sendMessage" method="post">
                        <textarea name="content" placeholder="Votre message..."></textarea>
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">
                        <button type="submit">Envoyer</button>
                    </form>
                </div>

            </div>
        <?php } ?>
    </div>
</main>