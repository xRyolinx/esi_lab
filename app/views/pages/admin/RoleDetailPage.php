<?php
require_once __DIR__ . '/../../../models/Roles.php';
require_once __DIR__ . '/../../../models/Permissions.php';
require_once __DIR__ . '/../../../models/RolePermission.php';
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class RoleDetailPage extends AuthTemplate
{
    private $role;
    private $permissions;
    private $rolePermissions;
    public function __construct($title = 'Détail du rôle', array $data = [])
    {
        parent::__construct($title);
        $this->role = $data['role'] ?? [];
        $this->permissions = $data['permissions'] ?? [];
        $this->rolePermissions = $data['rolePermissions'] ?? [];
    }
    protected function content()
    {
        $canEdit = SessionManager::hasPermissions(['roles.write']);
        ?>
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Rôle : <?= htmlspecialchars($this->role['nom_role']) ?></h1>
                <?php if ($canEdit): ?>
                    <form method="POST" action="/admin/roles/<?= urlencode($this->role['nom_role']) ?>"
                        onsubmit="return confirm('Supprimer ce rôle ?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 ml-2">Supprimer</button>
                    </form>
                <?php endif; ?>
            </div>
            <p class="mb-6 text-gray-700">Description : <?= htmlspecialchars($this->role['description']) ?></p>
            <form method="POST" action="/admin/roles/<?= urlencode($this->role['nom_role']) ?>/permissions">
                <input type="hidden" name="_method" value="PUT">
                <h2 class="text-lg font-semibold mb-2">Permissions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <?php foreach ($this->permissions as $perm): ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" <?php
                            if (!$canEdit) {
                                echo 'disabled';
                            }
                            ?> name="permissions[]"
                                value="<?= htmlspecialchars($perm['nom_permission']) ?>" <?php if (in_array($perm['nom_permission'], $this->rolePermissions))
                                      echo 'checked'; ?> class="form-checkbox h-5 w-5 text-secondary">
                            <?= htmlspecialchars($perm['description']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php
                if ($canEdit): ?>
                    <button type="submit"
                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark transition">Enregistrer</button>
                <?php endif;
                ?>
            </form>
        </div>
        <?php
    }
}
