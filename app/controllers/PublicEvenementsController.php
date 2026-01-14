<?php
require_once __DIR__ . '/../models/Evenements.php';
require_once __DIR__ . '/../models/InscriptionEvenement.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../views/pages/user/EvenementsPage.php';
require_once __DIR__ . '/../views/pages/user/SingleEvenementPage.php';

class PublicEvenementsController
{
    // Liste des événements (cards + filtres)
    public function index()
    {
        $filters = [];
        if (!empty($_GET['type'])) {
            $filters['type'] = ['valeur' => $_GET['type']];
        }
        if (!empty($_GET['date_debut'])) {
            $filters['date_debut'] = ['valeur' => $_GET['date_debut']];
        }
        $isLoggedIn = SessionManager::isLoggedIn();
        if ($isLoggedIn && isset($_GET['isPublic']) && $_GET['isPublic'] !== '') {
            $filters['isPublic'] = ['valeur' => (int) $_GET['isPublic']];
        } else {
            // Si non connecté, on ne filtre pas sur isPublic (on affiche tout)
        }
        $conditions = [];
        if (!empty($filters)) {
            $conditions[] = $filters;
        }
        $evenements = Evenements::getAll(
            conditions: $conditions
        );
        $page = new EvenementsPage('Événements', [
            'evenements' => $evenements,
            'isLoggedIn' => $isLoggedIn
        ]);
        $page->render();
    }

    // Détail d'un événement + inscription
    public function singleEvenement($id_evenement)
    {
        // get event
        $event = Evenements::getUnique(
            conditions: [['id_evenement' => ['valeur' => $id_evenement]]]
        );
        if (!$event) {
            SessionManager::setFlashMessage("error", "Événement introuvable.");
            header("Location: /evenements", true, 404);
            exit;
        }

        // Si privé, user doit être connecté
        if (!$event['isPublic'] && !SessionManager::isLoggedIn()) {
            SessionManager::setFlashMessage("error", "Vous devez être connecté pour accéder à cet événement privé.");
            header("Location: /evenements", true, 403);
            exit;
        }

        // get user id or null
        $userId = SessionManager::getUserId();

        // check if already inscrit
        $isDejaInscrit = $this->isUserInscrit($id_evenement, $userId);

        // render
        $page = new SingleEvenementPage('Détail événement', [
            'evenement' => $event,
            'isInscrit' => $isDejaInscrit,
        ]);
        $page->render();
    }

    public function inscrire($id_evenement)
    {
        // get event
        $event = Evenements::getUnique(
            conditions: [['id_evenement' => ['valeur' => $id_evenement]]]
        );
        if (!$event) {
            SessionManager::setFlashMessage("error", "Événement introuvable.");
            header("Location: /evenements");
            exit;
        }

        // Si privé, user doit être connecté
        if (!$event['isPublic'] && !SessionManager::isLoggedIn()) {
            SessionManager::setFlashMessage("error", "Vous devez être connecté pour accéder à cet événement privé.");
            header("Location: /evenements");
            exit;
        }

        // get user id or null
        $userId = SessionManager::getUserId();

        // check if already inscrit
        $isDejaInscrit = $this->isUserInscrit($id_evenement, $userId);
        if ($isDejaInscrit) {
            SessionManager::setFlashMessage("error", "Vous êtes déjà inscrit à cet événement.");
            header("Location: /evenements/{$id_evenement}");
            exit;
        }

        // create inscription
        InscriptionEvenement::create([
            'id_evenement' => $id_evenement,
            'id_user' => $userId,
            'date_inscription' => date('Y-m-d H:i:s')
        ]);

        // redirect
        SessionManager::setFlashMessage("success", "Inscription réussie à l'événement.");
        header("Location: /evenements/{$id_evenement}", true, 302);
        exit;
    }

    private function isUserInscrit($id_evenement, $id_user)
    {
        if (!$id_user) {
            return false;
        }
        $inscription = InscriptionEvenement::getUnique(
            conditions: [
                [
                    'id_evenement' => ['valeur' => $id_evenement],
                    'id_user' => ['valeur' => $id_user]
                ]
            ]
        );
        return !empty($inscription);
    }
}
