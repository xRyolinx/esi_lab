<?php
require_once __DIR__ . '/../models/Actualites.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../views/pages/admin/ActualitesPage.php';
require_once __DIR__ . '/../views/pages/admin/CreateActualitePage.php';
require_once __DIR__ . '/../views/pages/admin/EditActualitePage.php';
require_once __DIR__ . '/../views/pages/admin/SingleActualitePage.php';

class ActualitesController
{
    public function allActualitesPage()
    {
        $actualites = Actualites::getAll();
        $page = new ActualitesPage('Gestion des actualités', ['actualites' => $actualites]);
        $page->render();
    }

    public function createActualitePage()
    {
        $page = new CreateActualitePage('Créer une actualité');
        $page->render();
    }

    public function create()
    {
        $fields = ['titre', 'description', 'type'];
        $check = true;
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/actualites/new');
            exit;
        }
        $a = Actualites::create([
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type']
        ]);
        SessionManager::setFlashMessage('success', 'Actualité créée.');
        header('Location: /admin/actualites/' . urlencode($a['id_actualite']));
        exit;
    }

    public function singleActualitePage($id_actualite)
    {
        $a = Actualites::getUnique(conditions: [['id_actualite' => ['valeur' => $id_actualite]]]);
        if (!$a) {
            SessionManager::setFlashMessage('error', "Actualité introuvable.");
            header('Location: /admin/actualites');
            exit;
        }
        $page = new SingleActualitePage('Actualité', ['actualite' => $a]);
        $page->render();
    }

    public function editActualitePage($id_actualite)
    {
        $a = Actualites::getUnique(conditions: [['id_actualite' => ['valeur' => $id_actualite]]]);
        if (!$a) {
            SessionManager::setFlashMessage('error', "Actualité introuvable.");
            header('Location: /admin/actualites');
            exit;
        }
        $page = new EditActualitePage('Modifier actualité', ['actualite' => $a]);
        $page->render();
    }

    public function edit($id_actualite)
    {
        $fields = ['titre', 'description', 'type'];
        $check = true;
        foreach ($fields as $f) {
            if (empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!$check) {
            header('Location: /admin/actualites/' . urlencode($id_actualite) . '/edit');
            exit;
        }
        $a = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type']
        ];
        Actualites::edit($a, 'id_actualite', $id_actualite);
        SessionManager::setFlashMessage('success', 'Actualité modifiée.');
        header('Location: /admin/actualites/' . urlencode($id_actualite));
        exit;
    }

    public function delete($id_actualite)
    {
        Actualites::delete([
            ['id_actualite' => ['valeur' => $id_actualite]]
        ]);
        SessionManager::setFlashMessage('success', 'Actualité supprimée.');
        header('Location: /admin/actualites');
        exit;
    }
}
