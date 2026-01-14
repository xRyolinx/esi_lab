<?php
require_once __DIR__ . '/../models/Equipes.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../views/pages/user/EquipesPage.php';

class PublicEquipesController
{
    // Page publique des équipes
    public function index()
    {
        // Présentation labo
        $labInfo = [
            'description' => "Le laboratoire de l'ESI est le premier lab d'informatique en Algérie, et parmi les meilleurs en Afrique.",
            'themes' => [
                'Informatique',
                'Mathématiques',
                'Intelligence Artificielle',
            ],
        ];

        // Récupérer toutes les équipes avec chef et membres
        $equipes = Equipes::getAll(include: ['membres']);
        foreach ($equipes as &$equipe) {
            // Chef d'équipe
            $chef = null;
            if (!empty($equipe['id_chef'])) {
                $chef = Users::getUnique(conditions: [['id_user' => ['valeur' => $equipe['id_chef']]]]);
            }
            $equipe['chef'] = $chef;
            // Membres
            $equipe['membres'] = $equipe['membres'] ?? [];
        }

        // Directeur labo (ex: user avec role Directeur)
        $directeur = Users::getUnique(conditions: [['poste' => ['valeur' => 'directeur']]]);

        // Membres ayant un poste (hors chef/directeur)
        $postes = Users::getAll(conditions: [
            ['poste' => ['comparaison' => 'IS NOT NULL']],
            ['poste' => ['comparaison' => '!=', 'valeur' => '']],
            ['role' => ['comparaison' => '!=', 'valeur' => 'Directeur']],
        ]);

        // Tous les users (pour affichage global si besoin)
        $users = Users::getAll();

        $page = new EquipesPage('Les équipes de recherche', [
            'labInfo' => $labInfo,
            'equipes' => $equipes,
            'users' => $users,
            'directeur' => $directeur,
            'postes' => $postes,
        ]);
        $page->render();
    }

    // Page détail d'une équipe publique
    public function singleEquipe($id_equipe)
    {
        require_once __DIR__ . '/../views/pages/user/SingleEquipePage.php';
        $equipe = Equipes::getUnique(
            conditions: [[Equipes::$pk => ['valeur' => $id_equipe]]],
            include: ['membres', 'publications']
        );
        if (!$equipe) {
            http_response_code(404);
            echo "Équipe introuvable.";
            return;
        }
        // Chef
        $chef = null;
        if (!empty($equipe['id_chef'])) {
            $chef = Users::getUnique(conditions: [['id_user' => ['valeur' => $equipe['id_chef']]]]);
        }
        $equipe['chef'] = $chef;
        $equipe['membres'] = $equipe['membres'] ?? [];
        $equipe['publications'] = $equipe['publications'] ?? [];
        $page = new SingleEquipePage('Détail équipe', [
            'equipe' => $equipe
        ]);
        $page->render();
    }

    // Page perso d'un membre (user)
    public function singleUser($id_user)
    {
        require_once __DIR__ . '/../views/pages/user/SingleUserPage.php';
        $user = Users::getUnique(
            conditions: [['id_user' => ['valeur' => $id_user]]],
            include: ['equipe', 'publications', 'projets']
        );
        if (!$user) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }
        $user['equipe'] = $user['equipe'] ?? null;
        $user['publications'] = $user['publications'] ?? [];
        $user['projets'] = $user['projets'] ?? [];
        $page = new SingleUserPage('Profil membre', [
            'user' => $user
        ]);
        $page->render();
    }
}
