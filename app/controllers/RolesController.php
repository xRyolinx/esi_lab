<?php
require_once __DIR__ . '/../models/Roles.php';
require_once __DIR__ . '/../models/Permissions.php';
require_once __DIR__ . '/../models/RolePermission.php';
require_once __DIR__ . '/../views/pages/admin/RolesListPage.php';
require_once __DIR__ . '/../views/pages/admin/RoleDetailPage.php';
require_once __DIR__ . '/../views/pages/admin/CreateRolePage.php';

class RolesController
{
    // --------------- pages ---------------
    // Page de la liste des roles
    public function allRolesPage()
    {
        $roles = Roles::getAll();
        $page = new RolesListPage('Gestion des rôles', ['roles' => $roles]);
        $page->render();
    }

    // Page d'un role et ses details
    public function singleRolePage($nom_role)
    {
        // get roll
        $role = Roles::getUnique(conditions: [['nom_role' => ['valeur' => $nom_role]]]);
        if (!$role) {
            http_response_code(404);
            echo "Rôle inexistant";
            return;
        }

        // get permissions and role permissions
        $permissions = Permissions::getAll();
        $rolePerms = RolePermission::getAll(conditions: [['nom_role' => ['valeur' => $nom_role]]]);
        $rolePermissions = array_map(fn($rp) => $rp['nom_permission'], $rolePerms);

        // render
        $page = new RoleDetailPage("Rôle : $nom_role", [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
        $page->render();
    }

    // Page de création de rôle
    public function createRolePage()
    {
        $page = new CreateRolePage('Créer un rôle');
        $page->render();
    }


    // --------------- actions ---------------
    // Création de rôle
    public function createRole()
    {
        $nom_role = trim($_POST['nom_role'] ?? '');
        $nom_role = str_replace(' ', '_', $nom_role);
        $description = trim($_POST['description'] ?? '');

        $check = true;
        if ($nom_role === '') {
            SessionManager::setFlashMessage('error', 'Le nom du rôle est requis.');
            $check = false;
        }
        // Vérifier unicité
        if (Roles::getUnique(conditions: [['nom_role' => ['valeur' => $nom_role]]])) {
            SessionManager::setFlashMessage('error', 'Ce nom de rôle existe déjà.');
            $check = false;
        }
        if (!$check) {
            self::createRolePage();
            return;
        }

        // Créer le rôle
        Roles::create([
            'nom_role' => $nom_role,
            'description' => $description
        ]);
        header('Location: /admin/roles');
        exit;
    }

    // Mise à jour des permissions d'un rôle
    public function updateRolePermissions($nom_role)
    {
        // check role
        $role = Roles::getUnique(conditions: [['nom_role' => ['valeur' => $nom_role]]]);
        if (!$role) {
            SessionManager::setFlashMessage('error', 'Rôle inexistant.');
            header('Location: /admin/roles');
            exit;
        }

        // new permissions
        $permissions = $_POST['permissions'] ?? [];
        if (!is_array($permissions))
            $permissions = [];

        // Supprimer les permissions existantes        
        RolePermission::delete([
            ['nom_role' => ['valeur' => $nom_role]]
        ]);

        // Ajouter les nouvelles permissions
        foreach ($permissions as $perm) {
            RolePermission::create([
                'nom_role' => $nom_role,
                'nom_permission' => $perm
            ]);
        }

        // fin
        SessionManager::setFlashMessage('success', 'Permissions mises à jour.');
        header('Location: /admin/roles/' . urlencode($nom_role));
        exit;
    }

    // Suppression d'un rôle
    public function deleteRole($nom_role)
    {
        $role = Roles::getUnique(conditions: [['nom_role' => ['valeur' => $nom_role]]]);
        if (!$role) {
            SessionManager::setFlashMessage('error', 'Rôle inexistant.');
        } else {
            Roles::delete([
                ['nom_role' => ['valeur' => $nom_role]]
            ]);
        }

        // Rediriger vers la liste des rôles
        header('Location: /admin/roles');
        exit;
    }
}
