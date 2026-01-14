<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Evenements.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../views/pages/admin/EventsPage.php';

class EventsController {
    public function allEventsPage() {
        require_once __DIR__ . '/../views/pages/admin/EventsPage.php';
        $events = Evenements::getAll();
        $page = new EventsPage('Gestion des événements', ['events' => $events]);
        $page->render();
    }

    public function createEventPage() {
        require_once __DIR__ . '/../views/pages/admin/CreateEventPage.php';
        $page = new CreateEventPage('Créer un événement');
        $page->render();
    }

    public function create() {
        $fields = ['titre', 'description', 'type', 'isPublic', 'lieu', 'date_debut', 'date_fin', 'nb_max_participants'];
        $required_fields = ['titre', 'type', 'date_debut', 'date_fin'];
        $check = true;
        foreach ($fields as $f) {
            if (in_array($f, $required_fields) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/evenements/new');
            exit;
        }

        // nb participants
        if (isset($_POST['nb_max_participants']) && ($_POST['nb_max_participants'] == 0 || $_POST['nb_max_participants'] == null)) {
            $_POST['nb_max_participants'] = null;
        }
        $event = Evenements::create([
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? '',
            'isPublic' => !empty($_POST['isPublic']) ? 1 : 0,
            'lieu' => $_POST['lieu'] ?? '',
            'date_debut' => $_POST['date_debut'],
            'date_fin' => $_POST['date_fin'],
            'nb_max_participants' => $_POST['nb_max_participants']
        ]);
        SessionManager::setFlashMessage('success', 'Événement créé.');
        header('Location: /admin/evenements/' . urlencode($event['id_evenement']));
        exit;
    }

    public function singleEventPage($id_evenement) {
        require_once __DIR__ . '/../views/pages/admin/SingleEventPage.php';
        $event = Evenements::getUnique(conditions: [['id_evenement' => ['valeur' => $id_evenement]]], include: ['inscrits']);
        if (!$event) {
            SessionManager::setFlashMessage('error', "Événement introuvable.");
            header('Location: /admin/evenements');
            exit;
        }
        $inscrits = $event['inscrits'] ?? [];
        $page = new SingleEventPage('Événement', ['event' => $event, 'inscrits' => $inscrits]);
        $page->render();
    }

    public function editEventPage($id_evenement) {
        require_once __DIR__ . '/../views/pages/admin/EditEventPage.php';
        $event = Evenements::getUnique(conditions: [['id_evenement' => ['valeur' => $id_evenement]]]);
        if (!$event) {
            SessionManager::setFlashMessage('error', "Événement introuvable.");
            header('Location: /admin/evenements');
            exit;
        }
        $page = new EditEventPage('Modifier événement', ['event' => $event]);
        $page->render();
    }

    public function edit($id_evenement) {
        $fields = ['titre', 'description', 'type', 'isPublic', 'lieu', 'date_debut', 'date_fin', 'nb_max_participants'];
        $required_fields = ['titre', 'type', 'date_debut', 'date_fin'];
        $check = true;
        foreach ($fields as $f) {
            if (in_array($f, $required_fields) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/evenements/' . urlencode($id_evenement) . '/edit');
            exit;
        }

        // nb participants
        if (isset($_POST['nb_max_participants']) && ($_POST['nb_max_participants'] == 0 || $_POST['nb_max_participants'] == null)) {
            $_POST['nb_max_participants'] = null;
        }
        $event = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? '',
            'isPublic' => !empty($_POST['isPublic']) ? 1 : 0,
            'lieu' => $_POST['lieu'] ?? '',
            'date_debut' => $_POST['date_debut'],
            'date_fin' => $_POST['date_fin'],
            'nb_max_participants' => $_POST['nb_max_participants']
        ];
        Evenements::edit($event, 'id_evenement', $id_evenement);
        SessionManager::setFlashMessage('success', 'Événement modifié.');
        header('Location: /admin/evenements/' . urlencode($id_evenement));
        exit;
    }

    public function delete($id_evenement) {
        Evenements::delete([
            ['id_evenement' => ['valeur' => $id_evenement]]
        ]);
        SessionManager::setFlashMessage('success', 'Événement supprimé.');
        header('Location: /admin/evenements');
        exit;
    }
}
