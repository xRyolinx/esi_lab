<?php
require_once __DIR__ . '/../models/Parametres.php';
require_once __DIR__ . '/../views/pages/admin/ParametresPage.php';
require_once __DIR__ . '/../config/SessionManager.php';

class ParametresController
{
    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            $this->get();
        } elseif ($method === 'POST') {
            $this->save();
        } else {
            http_response_code(405);
            echo "Méthode non autorisée.";
        }
    }

    public function get()
    {
        if (!SessionManager::hasPermissions(['parametres.write'])) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }
        $parametres = Parametres::getAll();
        $page = new ParametresPage('Paramètres', [
            'parametres' => $parametres,
            'success' => null,
            'error' => null
        ]);
        $page->render();
    }

    public function save()
    {
        if (!SessionManager::hasPermissions(['parametres.write'])) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }
        $success = null;
        $error = null;
        if (isset($_POST['valeurs']) && is_array($_POST['valeurs'])) {
            foreach ($_POST['valeurs'] as $cle => $valeur) {
                Parametres::edit(['valeur' => $valeur], 'cle', $cle);
            }
            $success = true;
        }
        SessionManager::setFlashMessage("success", "Paramètres mis à jour avec succès.");
        header("Location: /admin/parametres");
        exit;
    }
}
