<?php
require_once __DIR__ . '/../../../models/Roles.php';
require_once __DIR__ . '/../../../models/Permissions.php';
require_once __DIR__ . '/../../../models/RolePermission.php';
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class RolesListPage extends AuthTemplate
{
    private $roles;
    public function __construct($title = 'Gestion des rôles', array $data = [])
    {
        parent::__construct($title);
        $this->roles = $data['roles'] ?? [];
    }
    protected function content()
    {
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Rôles</h1>
                <?php if (SessionManager::hasPermissions(['roles.write'])): ?>
                    <a href="/admin/roles/new"
                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Nouveau rôle
                    </a>
                <?php endif; ?>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">Nom du rôle</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->roles as $role): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <a href="/admin/roles/<?= urlencode($role['nom_role']) ?>"
                                        class="text-secondary hover:underline">
                                        <?= htmlspecialchars($role['nom_role']) ?>
                                    </a>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($role['description']) ?></td>
                                <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                    <a href="/admin/roles/<?= urlencode($role['nom_role']) ?>"
                                        class="text-primary hover:underline">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
