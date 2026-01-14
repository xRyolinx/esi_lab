<?php
require_once __DIR__ . '/../models/Contacts.php';
require_once __DIR__ . '/../views/pages/user/ContactPage.php';

class PublicContactController
{
    public function index()
    {
        $page = new ContactPage('Contact', [
        ]);
        $page->render();
    }

    public function envoyer()
    {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $error = [];
        if (!$nom || !$email || !$sujet || !$message) {
            $error[] = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email invalide.";
        }
        if (!empty($error)) {
            SessionManager::setFlashMessage("error", implode(" ", $error));
            header("Location: /contact");
            exit;
        }
        
        // create
        Contacts::create([
            'nom' => $nom,
            'email' => $email,
            'sujet' => $sujet,
            'message' => $message,
            'date_envoi' => date('Y-m-d H:i:s'),
        ]);
        SessionManager::setFlashMessage("success", "Votre message a été envoyé avec succès.");
        header("Location: /contact");
        exit;        
    }
}
