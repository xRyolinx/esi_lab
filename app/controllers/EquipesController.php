<?php
require_once __DIR__ . '/../models/Equipes.php';
require_once __DIR__ . '/../models/PublicationAuteur.php';
require_once __DIR__ . '/../views/pages/admin/EquipesPage.php';
require_once __DIR__ . '/../views/pages/admin/SingleEquipePage.php';
require_once __DIR__ . '/../views/pages/admin/CreateEquipePage.php';
require_once __DIR__ . '/../config/SessionManager.php';


class EquipesController
{
    // -------------- pages --------------
    // Liste toutes les équipes
    public function allEquipesPage()
    {
        // $equipes = Equipes::getAll(include: ['membres', 'nb_pubs', 'ressources']);
        $equipes = Equipes::getAll(include: ['membres']);
        $page = new EquipesPage('Liste des équipes', ['equipes' => $equipes]);
        $page->render();
    }

    // Page d'une équipe
    public function singleEquipePage($id_equipe)
    {
        $equipe = Equipes::getUnique(
            conditions: [[Equipes::$pk => ['valeur' => $id_equipe]]],
            include: ['membres', 'nb_pubs', 'ressources']
        );
        if (!$equipe) {
            SessionManager::setFlashMessage('error', "Équipe introuvable.");
            header('Location: /admin/equipes');
            exit;
        }
        $page = new SingleEquipePage('Détail équipe', ['equipe' => $equipe]);
        $page->render();
    }

    // Page de création
    public function createEquipePage()
    {
        $page = new CreateEquipePage('Créer une équipe');
        $page->render();
    }


    // -------------- actions --------------
    // Créer une équipe
    public function createEquipe()
    {
        $fields = ['nom_equipe', 'description'];
        $check = true;
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            self::createEquipePage();
            return;
        }
        $equipe = [
            'nom_equipe' => $_POST['nom_equipe'],
            'description' => $_POST['description'],
            'date_creation' => date('Y-m-d H:i:s'),
        ];
        Equipes::create($equipe);
        SessionManager::setFlashMessage('success', 'Équipe créée.');
        header('Location: /admin/equipes');
        exit;
    }

    // Modifier une équipe
    public function updateEquipe($id_equipe)
    {
        $fields = ['nom_equipe', 'description'];
        $check = true;
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/equipes/' . urlencode($id_equipe));
            exit;
        }
        $equipe = [
            'nom_equipe' => $_POST['nom_equipe'],
            'description' => $_POST['description'],
        ];
        Equipes::edit($equipe, 'id_equipe', $id_equipe);
        SessionManager::setFlashMessage('success', 'Équipe modifiée.');
        header('Location: /admin/equipes/' . urlencode($id_equipe));
        exit;
    }

    // Supprimer une équipe
    public function deleteEquipe($id_equipe)
    {
        Equipes::delete([
            ['id_equipe' => ['valeur' => $id_equipe]]
        ]);
        SessionManager::setFlashMessage('success', 'Équipe supprimée.');
        header('Location: /admin/equipes');
        exit;
    }

    // Ajouter un user sans équipe
    public function addUserToEquipe($id_equipe)
    {
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Aucun utilisateur sélectionné.');
            header('Location: /admin/equipes/' . urlencode($id_equipe));
            exit;
        }
        Users::edit(['id_equipe' => $id_equipe], 'id_user', $id_user);
        SessionManager::setFlashMessage('success', 'Utilisateur ajouté à l\'équipe.');
        header('Location: /admin/equipes/' . urlencode($id_equipe));
        exit;
    }

    // Retirer un membre de l'équipe
    public function removeUserFromEquipe($id_equipe)
    {
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Aucun utilisateur sélectionné.');
            header('Location: /admin/equipes/' . urlencode($id_equipe));
            exit;
        }

        // On retire l'utilisateur de l'équipe
        Users::edit(['id_equipe' => null], 'id_user', $id_user);

        // si chef, on le retire aussi
        $equipe = Equipes::getUnique(conditions: [[Equipes::$pk => ['valeur' => $id_equipe]]]);
        if ($equipe['id_chef'] == $id_user) {
            Equipes::edit(['id_chef' => null], 'id_equipe', $id_equipe);
        }

        SessionManager::setFlashMessage('success', 'Membre retiré de l\'équipe.');
        header('Location: /admin/equipes/' . urlencode($id_equipe));
        exit;
    }

    // Définir le chef d'équipe
    public function setChefEquipe($id_equipe)
    {
        $id_chef = $_POST['id_chef'] ?? null;
        if (!$id_chef) {
            SessionManager::setFlashMessage('error', 'Aucun chef sélectionné.');
            header('Location: /admin/equipes/' . urlencode($id_equipe));
            exit;
        }
        Equipes::edit(['id_chef' => $id_chef], 'id_equipe', $id_equipe);
        SessionManager::setFlashMessage('success', 'Chef d\'équipe défini.');
        header('Location: /admin/equipes/' . urlencode($id_equipe));
        exit;
    }
}
