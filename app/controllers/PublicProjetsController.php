<?php
require_once __DIR__ . '/../models/Projets.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Partenaires.php';
require_once __DIR__ . '/../models/Publications.php';
require_once __DIR__ . '/../views/pages/user/ProjetsPage.php';
require_once __DIR__ . '/../views/pages/user/SingleProjetPage.php';

class PublicProjetsController
{
    // Liste des projets avec filtres
    public function index()
    {
        $filters = [];
        if (!empty($_GET['thematique'])) {
            $filters['thematique'] = ['valeur' => $_GET['thematique']];
        }
        if (!empty($_GET['financement'])) {
            $filters['type_financement'] = ['valeur' => $_GET['financement']];
        }
        if (!empty($_GET['statut'])) {
            $filters['statut'] = ['valeur' => $_GET['statut']];
        }
        if (!empty($_GET['date_debut'])) {
            $filters['date_debut'] = ['comparaison' => '>=' ,'valeur' => $_GET['date_debut']];
        }
        $conditions = [];
        if (!empty($filters)) {
            $conditions[] = $filters;
        }
        $projets = Projets::getAll(
            conditions: $conditions,
            include: []
        );
        $page = new ProjetsPage('Liste des projets', [
            'projets' => $projets
        ]);
        $page->render();
    }

    // Détail d'un projet
    public function singleProjet($id_projet)
    {
        $projet = Projets::getUnique(
            conditions: [['id_projet' => ['valeur' => $id_projet]]],
            include: ['users', 'partenaires', 'publications']
        );
        if (!$projet) {
            http_response_code(404);
            echo "Projet introuvable.";
            return;
        }
        // Chef du projet
        $chef = null;
        if (!empty($projet['id_responsable'])) {
            $chef = Users::getUnique(
                conditions: [['id_user' => ['valeur' => $projet['id_responsable']]]]
            );
        }
        // Membres
        $membres = $projet['users'] ?? [];
        // Partenaires
        $partenaires = $projet['partenaires'] ?? [];
        // Publications des membres
        $publications = $projet['publications'] ?? [];
        $page = new SingleProjetPage('Détail du projet', [
            'projet' => [
                'titre' => $projet['titre'] ?? '',
                'chef' => $chef,
                'membres' => $membres,
                'partenaires' => $partenaires,
                'publications' => $publications
            ]
        ]);
        $page->render();
    }
}
