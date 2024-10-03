<?php
?>

<body>
    <main class="container">
        <aside class="sidebar">
            <h2>Messagerie</h2>
            <div class="conversations-wrapper">
                <ul class="conversations">
                    <li class="conversation">
                        <img src="profil1.jpg" alt="Photo de profil">
                        <div class="conversation-info">
                            <span class="name">${user.username}</span>
                            <span class="last-message">Dernier message...</span>
                            <span class="timestamp">12:30</span>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="chat">
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