<?php
require_once __DIR__ . '/../models/Reservations.php';
require_once __DIR__ . '/../views/pages/admin/ReservationsPage.php';
require_once __DIR__ . '/../config/SessionManager.php';

class ReservationsController
{
    public function index()
    {
        if (!SessionManager::hasPermissions(['equipements.read'])) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }
        $reservations = Reservations::getAll(
            include: ['equipement', 'user']
        );
        $page = new ReservationsPage('Historique des réservations', [
            'reservations' => $reservations
        ]);
        $page->render();
    }
}
