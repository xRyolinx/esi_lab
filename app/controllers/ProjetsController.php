<?php
require_once __DIR__ . '/../models/Projets.php';
require_once __DIR__ . '/../models/ProjetUser.php';
require_once __DIR__ . '/../models/ProjetPartenaire.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Partenaires.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../views/pages/admin/ProjetsPage.php';
require_once __DIR__ . '/../views/pages/admin/SingleProjetPage.php';
require_once __DIR__ . '/../views/pages/admin/CreateProjetPage.php';

class ProjetsController
{
    // -------------- pages --------------
    // Liste des projets
    public function allProjetsPage()
    {
        $projets = Projets::getAll();
        $page = new ProjetsPage('Gestion des projets', [
            'projets' => $projets,
        ]);
        $page->render();
    }
    public function singleProjetPage($id_projet)
    {
        // get project with users and partenaires
        $projet = Projets::getUnique(
            conditions: [['id_projet' => ['valeur' => $id_projet]]],
            include: ['users', 'partenaires']
        );
        if (!$projet) {
            SessionManager::setFlashMessage('error', "Projet introuvable.");
            header('Location: /admin/projets');
            exit;
        }

        // get users dispo
        $users_disponibles = Users::getAll(conditions: [
            ['id_user' => ['comparaison' => 'NOT IN', 'valeur' => array_map(fn($u) => $u['id_user'], $projet['users'] ?? [])]]
        ]);

        // get partenaires dispo
        $partenaires_disponibles = Partenaires::getAll(conditions: [
            ['id_partenaire' => ['comparaison' => 'NOT IN', 'valeur' => array_map(fn($p) => $p['id_partenaire'], $projet['partenaires'] ?? [])]]
        ]);

        $page = new SingleProjetPage('Projet', [
            'projet' => $projet,
            'users_disponibles' => $users_disponibles,
            'partenaires_disponibles' => $partenaires_disponibles
        ]);
        $page->render();
    }
    public function createProjetPage()
    {
        $page = new CreateProjetPage('Créer un projet');
        $page->render();
    }

    // -------------- actions --------------
    // Créer un projet
    public function create()
    {
        $fields = ['titre', 'description', 'thematique', 'type_financement', 'date_debut', 'date_fin'];
        $notRequired = ['description', 'thematique', 'type_financement', 'date_fin'];

        $check = true;
        foreach ($fields as $f) {
            if (!in_array($f, $notRequired) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }

        if (!$check) {
            header('Location: /admin/projets/create');
            exit;
        }

        // creer projet
        $projet = Projets::create([
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'thematique' => $_POST['thematique'] ?? '',
            'type_financement' => $_POST['type_financement'] ?? '',
            'statut' => 'en_cours',
            'date_debut' => $_POST['date_debut'],
            'date_fin' => $_POST['date_fin'] ?? null,
            'id_responsable' => null
        ]);

        SessionManager::setFlashMessage('success', 'Projet créé.');
        header('Location: /admin/projets/' . urlencode($projet['id_projet']));
        exit;
    }

    // Modifier un projet
    public function edit($id_projet)
    {
        $fields = ['titre', 'description', 'thematique', 'type_financement', 'date_debut', 'date_fin', 'statut'];
        $notRequired = ['description', 'thematique', 'type_financement', 'date_fin'];

        $check = true;
        foreach ($fields as $f) {
            if (!in_array($f, $notRequired) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }

        if (!$check) {
            header('Location: /admin/projets/' . urlencode($id_projet));
            exit;
        }

        // editer projet
        $projet = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'thematique' => $_POST['thematique'] ?? '',
            'type_financement' => $_POST['type_financement'] ?? '',
            'date_debut' => $_POST['date_debut'],
            'date_fin' => $_POST['date_fin'] ?? null,
            'statut' => $_POST['statut']
        ];
        Projets::edit($projet, 'id_projet', $id_projet);

        SessionManager::setFlashMessage('success', 'Projet modifié.');
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Définir le responsable du projet
    public function setResponsable($id_projet)
    {
        $id_responsable = $_POST['id_responsable'] ?? null;

        // vérifier si l'utilisateur est membre du projet
        $membre = ProjetUser::getUnique(conditions: [
            [
                'id_user' => ['valeur' => $id_responsable],
                'id_projet' => ['valeur' => $id_projet]
            ]
        ]);
        if ($id_responsable && !$membre) {
            SessionManager::setFlashMessage('error', "L'utilisateur doit être membre du projet pour être responsable.");
            header('Location: /admin/projets/' . urlencode($id_projet));
            exit;
        }

        // edit
        Projets::edit([
            'id_responsable' => $id_responsable ?: null
        ], 'id_projet', $id_projet);

        // fin
        SessionManager::setFlashMessage('success', 'Responsable du projet mis à jour.');
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Supprimer un projet
    public function delete($id_projet)
    {
        Projets::delete([
            ['id_projet' => ['valeur' => $id_projet]]
        ]);
        SessionManager::setFlashMessage('success', 'Projet supprimé.');
        header('Location: /admin/projets');
        exit;
    }

    // Clôturer un projet (statut)
    public function cloturer($id_projet)
    {
        Projets::edit([
            'statut' => 'termine',
            'date_fin' => date('Y-m-d')
        ], 'id_projet', $id_projet);
        SessionManager::setFlashMessage('success', 'Projet clôturé.');
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Rouvrir un projet (statut)
    public function reouvrir($id_projet)
    {
        Projets::edit([
            'statut' => 'en_cours',
            'date_fin' => '0000-00-00'
        ], 'id_projet', $id_projet);
        SessionManager::setFlashMessage('success', 'Projet rouvert.');
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Associer un membre
    public function addUser($id_projet)
    {
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Utilisateur non spécifié.');
        } else {
            ProjetUser::create([
                'id_projet' => $id_projet,
                'id_user' => $id_user,
            ]);
            SessionManager::setFlashMessage('success', 'Utilisateur ajouté au projet.');
        }
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Retirer un membre
    public function removeUser($id_projet)
    {
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Utilisateur non spécifié.');
        } else {
            ProjetUser::delete([
                [
                    'id_projet' => ['valeur' => $id_projet],
                    'id_user' => ['valeur' => $id_user]
                ]
            ]);
            Projets::edit(['id_responsable' => null], 'id_projet', $id_projet);
            SessionManager::setFlashMessage('success', 'Utilisateur retiré du projet.');
        }
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Associer un partenaire
    public function addPartenaire($id_projet)
    {
        $id_partenaire = $_POST['id_partenaire'] ?? null;
        if (!$id_partenaire) {
            SessionManager::setFlashMessage('error', 'Partenaire non spécifié.');
        } else {
            ProjetPartenaire::create([
                'id_projet' => $id_projet,
                'id_partenaire' => $id_partenaire
            ]);
            SessionManager::setFlashMessage('success', 'Partenaire ajouté au projet.');
        }
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Retirer un partenaire
    public function removePartenaire($id_projet)
    {
        $id_partenaire = $_POST['id_partenaire'] ?? null;
        if (!$id_partenaire) {
            SessionManager::setFlashMessage('error', 'Partenaire non spécifié.');
        } else {
            ProjetPartenaire::delete([
                [
                    'id_projet' => ['valeur' => $id_projet],
                    'id_partenaire' => ['valeur' => $id_partenaire]
                ]
            ]);
            SessionManager::setFlashMessage('success', 'Partenaire retiré du projet.');
        }
        header('Location: /admin/projets/' . urlencode($id_projet));
        exit;
    }

    // Statistiques (exemple)
    public function stats()
    {
        $projets = Projets::getAll();
        // Par thématique
        $par_thematique = [];
        foreach ($projets as $p) {
            $th = $p['thematique'] ?: 'Non défini';
            $par_thematique[$th] = ($par_thematique[$th] ?? 0) + 1;
        }
        // Par responsable
        $par_responsable = [];
        foreach ($projets as $p) {
            $resp = 'Aucun';
            if (!empty($p['id_responsable'])) {
                $user = Users::getUnique(conditions: [['id_user' => ['valeur' => $p['id_responsable']]]]);
                if ($user)
                    $resp = $user['nom'] . ' ' . $user['prenom'];
            }
            $par_responsable[$resp] = ($par_responsable[$resp] ?? 0) + 1;
        }
        // Par année (afficher les projets dont année_debut <= année, et année_fin >= année)
        $par_annee = [];
        $annees = [];
        $currentYear = (int)date('Y');
        foreach ($projets as $p) {
            $debut = !empty($p['date_debut']) ? (int)substr($p['date_debut'], 0, 4) : null;
            if ($p['statut'] === 'en_cours') {
                $fin = $currentYear;
            } else {
                $fin = !empty($p['date_fin']) ? (int)substr($p['date_fin'], 0, 4) : $debut;
            }
            if ($debut) {
                $annees[] = $debut;
            }
            if ($fin) {
                $annees[] = $fin;
            }
        }
        if ($annees) {
            $min = min($annees);
            $max = max($annees);
            for ($an = $min; $an <= $max; $an++) {
                $par_annee[$an] = [];
                foreach ($projets as $p) {
                    $debut = !empty($p['date_debut']) ? (int)substr($p['date_debut'], 0, 4) : null;
                    if ($p['statut'] === 'en_cours') {
                        $fin = $currentYear;
                    } else {
                        $fin = !empty($p['date_fin']) ? (int)substr($p['date_fin'], 0, 4) : $debut;
                    }
                    // Afficher pour toutes les années entre debut et fin (fin = année courante si en_cours)
                    if ($debut && $fin && $debut <= $an && $fin >= $an) {
                        $par_annee[$an][] = $p;
                    }
                }
            }
        }
        require_once __DIR__ . '/../views/pages/admin/ProjetsStatsPage.php';
        $page = new ProjetsStatsPage('Statistiques projets', [
            'stats' => [
                'par_thematique' => $par_thematique,
                'par_responsable' => $par_responsable,
                'par_annee' => $par_annee
            ]
        ]);
        $page->render();
    }

    // Récupérer projets filtrés pour rapport PDF
    public function report()
    {
        $filters = [
            'annee' => $_GET['annee'] ?? null,
            'thematique' => $_GET['thematique'] ?? null,
            'encadrant' => $_GET['encadrant'] ?? null
        ];
        $conditions = [];
        if (!empty($filters['annee'])) {
            $conditions[] = ['date_debut' => ['comparaison' => 'LIKE', 'valeur' => $filters['annee'] . '%']];
        }
        if (!empty($filters['thematique'])) {
            $conditions[] = ['thematique' => ['valeur' => $filters['thematique']]];
        }
        $projets = Projets::getAll(conditions: $conditions);
        // Pour l'encadrant, filtrer côté PHP si besoin
        // ...génération PDF à compléter
    }
}
