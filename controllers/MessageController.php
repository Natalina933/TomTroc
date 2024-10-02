<?php
class MessageController
{
    public function showInbox(): void
    {
        // Récupérer les messages de la boîte de réception de l'utilisateur
        $inboxMessages = $this->messageManager->getInboxMessages($_SESSION['user']['id']);

        // Afficher la vue de la boîte de réception
        $this->renderView('inbox', 'Boîte de réception', ['messages' => $inboxMessages]);
    }

    public function showSentMessages(): void
    {
        // Récupérer les messages envoyés par l'utilisateur
        $sentMessages = $this->messageManager->getSentMessages($_SESSION['user']['id']);

        // Afficher la vue des messages envoyés
        $this->renderView('sentMessages', 'Messages envoyés', ['messages' => $sentMessages]);
    }

    public function showNewMessageForm(): void
    {
        // Récupérer la liste des destinataires possibles (si nécessaire)
        $recipients = $this->userManager->getAllUsers();

        // Afficher le formulaire de création de nouveau message
        $this->renderView('newMessageForm', 'Nouveau message', ['recipients' => $recipients]);
    }

    public function sendMessage(): void
    {
        // Valider et traiter les données du formulaire
        $recipientId = Utils::request('recipientId');
        $subject = Utils::request('subject');
        $content = Utils::request('content');

        // Créer et envoyer le message
        $message = new Message([
            'sender_id' => $_SESSION['user']['id'],
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'content' => $content
        ]);

        $this->MessageManager->sendMessage($message);

        // Rediriger vers la page des messages envoyés
        Utils::redirect('sentMessages');
    }

    // ... autres méthodes liées aux messages (répondre, supprimer, marquer comme lu/non lu)
}