<?php
require_once __DIR__ . '/../models/Equipements.php';
require_once __DIR__ . '/../models/Reservations.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../views/pages/admin/EquipementsPage.php';
require_once __DIR__ . '/../views/pages/admin/SingleEquipementPage.php';
require_once __DIR__ . '/../views/pages/admin/CreateEquipementPage.php';

class EquipementsController
{
    // ---------------- pages ---------------
    // Liste des équipements
    public function allEquipementsPage()
    {
        $equipements = Equipements::getAll();
        $page = new EquipementsPage('Liste des équipements', [
            'equipements' => $equipements
        ]);
        $page->render();
    }

    // Détail d'un équipement
    public function singleEquipementPage($id_equipement)
    {
        $equipement = Equipements::getUnique(
            conditions: [['id_equipement' => ['valeur' => $id_equipement]]],
            include: ['reservations' => ['user']]
        );
        if (!$equipement) {
            SessionManager::setFlashMessage('error', "Équipement introuvable.");
            header('Location: /admin/equipements');
            exit;
        }
        $users = SessionManager::hasPermissions(['equipements.write'])
            ? Users::getAll()
            : [];
        $page = new SingleEquipementPage('Équipement', [
            'equipement' => $equipement,
            'users' => $users
        ]);
        $page->render();
    }
    // Page de création d'équipement
    public function createEquipementPage()
    {
        $page = new CreateEquipementPage('Ajouter un équipement');
        $page->render();
    }


    // --------------- actions ---------------
    // Créer un équipement
    public function create()
    {
        $fields = ['nom', 'type', 'statut', 'localisation', 'description'];
        $required_fields = ['nom', 'type', 'statut'];
        foreach ($required_fields as $f) {
            if (empty($_POST[$f])) {
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
                header('Location: /admin/equipements/new');
                exit;
            }
        }
        Equipements::create([
            'nom' => $_POST['nom'],
            'type' => $_POST['type'],
            'statut' => $_POST['statut'],
            'localisation' => $_POST['localisation'] ?? '',
            'description' => $_POST['description'] ?? ''
        ]);
        SessionManager::setFlashMessage('success', 'Équipement créé.');
        header('Location: /admin/equipements');
        exit;
    }

    // Modifier un équipement
    public function edit($id_equipement)
    {
        $fields = ['nom', 'type', 'localisation', 'description'];
        $required_fields = ['nom', 'type'];
        foreach ($required_fields as $f) {
            if (empty($_POST[$f])) {
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
                header('Location: /admin/equipements/' . urlencode($id_equipement) . '/edit');
                exit;
            }
        }
        Equipements::edit([
            'nom' => $_POST['nom'],
            'type' => $_POST['type'],
            'localisation' => $_POST['localisation'] ?? '',
            'description' => $_POST['description'] ?? ''
        ], 'id_equipement', $id_equipement);
        SessionManager::setFlashMessage('success', 'Équipement modifié.');
        header('Location: /admin/equipements/' . urlencode($id_equipement));
        exit;
    }

    // Modifier le statut uniquement
    public function updateStatut($id_equipement)
    {
        $statut = $_POST['statut'] ?? null;
        if (!in_array($statut, ['disponible', 'maintenance'])) {
            SessionManager::setFlashMessage('error', 'Statut invalide.');
        } else {
            Equipements::edit(['statut' => $statut], 'id_equipement', $id_equipement);
            SessionManager::setFlashMessage('success', 'Statut mis à jour.');
            // TODO: notifier les utilisateurs ayant réservé si statut != disponible
        }
        header('Location: /admin/equipements/' . urlencode($id_equipement));
        exit;
    }

    // Supprimer un équipement
    public function delete($id_equipement)
    {
        Equipements::delete([
            ['id_equipement' => ['valeur' => $id_equipement]]
        ]);
        SessionManager::setFlashMessage('success', 'Équipement supprimé.');
        header('Location: /admin/equipements');
        exit;
    }

    // Réserver un équipement
    public function reserver($id_equipement)
    {
        $fields = ['date_debut', 'date_fin'];
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
                header('Location: /admin/equipements/' . urlencode($id_equipement));
                exit;
            }
        }

        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        // Vérifier que date_debut < date_fin
        if (strtotime($date_debut) >= strtotime($date_fin)) {
            SessionManager::setFlashMessage('error', "La date de début doit être antérieure à la date de fin.");
            header('Location: /admin/equipements/' . urlencode($id_equipement));
            exit;
        }

        $user_id = SessionManager::hasPermissions(['equipements.write'])
            ? ($_POST['user_id'] ?? null)
            : (SessionManager::getUserId() ?? null);

        if (!$user_id) {
            SessionManager::setFlashMessage('error', 'Utilisateur manquant.');
            header('Location: /admin/equipements/' . urlencode($id_equipement));
            exit;
        }

        // Vérifier l'absence de chevauchement
        $existing = Reservations::getAll(conditions: [
            [
                'id_equipement' => ['valeur' => $id_equipement],
                'date_debut' => ['comparaison' => '<=', 'valeur' => $date_fin],
                'date_fin' => ['comparaison' => '>=', 'valeur' => $date_debut],
            ],
        ]);
        if (count($existing) > 0) {
            SessionManager::setFlashMessage('error', "Ce créneau est déjà réservé pour cet équipement.");
            header('Location: /admin/equipements/' . urlencode($id_equipement));
            exit;
        }

        // reserver
        Reservations::create([
            'id_equipement' => $id_equipement,
            'id_user' => $user_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'date_reservation' => date('Y-m-d H:i:s'),
        ]);
        SessionManager::setFlashMessage('success', 'Réservation créée.');

        header('Location: /admin/equipements/' . urlencode($id_equipement));
        exit;
    }

    // Modifier une réservation
    public function editReservation($id_reservation)
    {
        // get id_equipement from reservation
        $reservation = Reservations::getUnique(conditions: [['id_reservation' => $id_reservation]]);
        $id_equipement = $reservation['id_equipement'] ?? null;

        // check inputs
        $fields = ['date_debut', 'date_fin'];
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
                header('Location: /admin/equipements/' . urlencode($id_equipement));
                exit;
            }
        }
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        // Vérifier que date_debut < date_fin
        if (strtotime($date_debut) >= strtotime($date_fin)) {
            SessionManager::setFlashMessage('error', "La date de début doit être antérieure à la date de fin.");
            header('Location: /admin/equipements/' . urlencode($id_equipement));
            exit;
        }


        // Vérifier l'absence de chevauchement
        $existing = Reservations::getAll(conditions: [
            [
                'id_equipement' => ['valeur' => $id_equipement],
                'date_debut' => ['comparaison' => '<=', 'valeur' => $date_fin],
                'date_fin' => ['comparaison' => '>=', 'valeur' => $date_debut],
                'id_reservation' => ['comparaison' => '!=', 'valeur' => $id_reservation],
            ],
        ]);
        if (count($existing) > 0) {
            SessionManager::setFlashMessage('error', "Ce créneau est déjà réservé pour cet équipement.");
            header('Location: /admin/equipements/' . urlencode($id_equipement));
            exit;
        }
        
        Reservations::edit([
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
        ], 'id_reservation', $id_reservation);
        SessionManager::setFlashMessage('success', 'Réservation modifiée.');
        
        // Rediriger vers l'équipement concerné
        header('Location: /admin/equipements/' . urlencode($id_equipement));
        exit;
    }

    // Supprimer une réservation
    public function deleteReservation($id_reservation)
    {
        // On récupère la réservation pour retrouver l'id de l'équipement
        $reservation = Reservations::getUnique(conditions: [['id_reservation' => ['valeur' => $id_reservation]]]);
        if (!$reservation) {
            SessionManager::setFlashMessage('error', "Réservation introuvable.");
            header('Location: /admin/equipements');
            exit;
        }
        $id_equipement = $reservation['id_equipement'];
        Reservations::delete([
            ['id_reservation' => ['valeur' => $id_reservation]]
        ]);
        SessionManager::setFlashMessage('success', 'Réservation supprimée.');
        header('Location: /admin/equipements/' . urlencode($id_equipement));
        exit;
    }
}
