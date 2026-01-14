<?php
require_once __DIR__ . '/../models/Contacts.php';
require_once __DIR__ . '/../views/pages/admin/AdminContactsPage.php';
require_once __DIR__ . '/../config/SessionManager.php';

class AdminContactsController
{
    public function index()
    {
        if (!SessionManager::hasPermissions(['contacts.read'])) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }
        $contacts = Contacts::getAll();
        $page = new AdminContactsPage('Contacts', [
            'contacts' => $contacts
        ]);
        $page->render();
    }
}
