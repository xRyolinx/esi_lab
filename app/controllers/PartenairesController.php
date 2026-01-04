<?php
require_once __DIR__ . '/../models/Partenaires.php';
require_once __DIR__ . '/../views/pages/admin/PartenairesPage.php';
require_once __DIR__ . '/../views/pages/admin/CreatePartenairePage.php';
require_once __DIR__ . '/../views/pages/admin/SinglePartenairePage.php';
require_once __DIR__ . '/../config/SessionManager.php';

class PartenairesController
{
    // Page de liste des partenaires
    public function allPartenairesPage()
    {
        $partenaires = Partenaires::getAll();
        $page = new PartenairesPage('Gestion des partenaires', ['partenaires' => $partenaires]);
        $page->render();
    }

    // Page d'un seul partenaire
    public function singlePartenairePage($id_partenaire)
    {
        // get partenaire
        $partenaire = Partenaires::getUnique(
            conditions: [['id_partenaire' => ['valeur' => $id_partenaire]]],
        );
        if (!$partenaire) {
            SessionManager::setFlashMessage('error', "Partenaire introuvable.");
            header('Location: /admin/partenaires');
            exit;
        }

        // render
        $page = new SinglePartenairePage('Partenaire', [
            'partenaire' => $partenaire,
        ]);
        $page->render();
    }

    // Page de création de partenaire
    public function createPartenairePage()
    {
        $page = new CreatePartenairePage('Créer un partenaire');
        $page->render();
    }

    // Créer partenaire
    public function createPartenaire()
    {
        $fields = ['nom', 'type', 'logo', 'site_web', 'description'];
        $required_fields = ['nom', 'type'];
        $check = true;
        foreach ($required_fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Champ $f requis.");
            }
        }
        if (!$check) {
            self::createPartenairePage();
            return;
        }
        $partenaire = [
            'nom' => $_POST['nom'],
            'type' => $_POST['type'],
            'logo' => $_POST['logo'] ?? '',
            'site_web' => $_POST['site_web'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];
        Partenaires::create($partenaire);
        header('Location: /admin/partenaires');
        exit;
    }

    // Modifier partenaire
    public function updatePartenaire($id_partenaire)
    {
        $fields = ['nom', 'type', 'logo', 'site_web', 'description'];
        $required_fields = ['nom', 'type'];
        $check = true;
        foreach ($required_fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Champ $f requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/partenaires/' . urlencode($id_partenaire));
            exit;
        }
        $partenaire = [
            'nom' => $_POST['nom'],
            'type' => $_POST['type'],
            'logo' => $_POST['logo'] ?? '',
            'site_web' => $_POST['site_web'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];
        Partenaires::edit($partenaire, 'id_partenaire', $id_partenaire);
        SessionManager::setFlashMessage('success', 'Partenaire mis à jour.');
        header('Location: /admin/partenaires/' . urlencode($id_partenaire));
        exit;
    }

    // Supprimer partenaire
    public function deletePartenaire($id_partenaire)
    {
        Partenaires::delete([
            ['id_partenaire' => ['valeur' => $id_partenaire]]
        ]);
        SessionManager::setFlashMessage('success', 'Partenaire supprimé.');
        header('Location: /admin/partenaires');
        exit;
    }
}
