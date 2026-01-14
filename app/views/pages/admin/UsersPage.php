<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../models/Users.php';
require_once __DIR__ . '/../../../models/Roles.php';
require_once __DIR__ . '/../../../models/Equipes.php';

class UsersPage extends AuthTemplate
{
    private $users;
    private $equipes;
    private $roles;


    public function __construct($title = 'Gestion des utilisateurs', array $data = [])
    {
        parent::__construct($title);
        $this->users = $data['users'] ?? [];
        $this->equipes = $data['equipes'] ?? [];
        $this->roles = $data['roles'] ?? [];
    }

    protected function content()
    {
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Utilisateurs</h1>
                <div class="flex gap-2">
                    <?php if (SessionManager::hasPermissions(['users.write'])): ?>
                        <a href="/admin/users/new"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                            <i class="fas fa-user-plus"></i> Créer utilisateur
                        </a>
                    <?php endif; ?>
                    <?php if (SessionManager::hasPermissions(['roles.read'])): ?>
                        <a href="/admin/roles"
                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark transition flex items-center gap-2">
                            <i class="fas fa-user-shield"></i> Voir les rôles
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <form method="GET" class="mb-6">
                <input type="text" name="search" placeholder="Rechercher... (nom, prénom, username, email)"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="border px-3 py-2 rounded w-full max-w-xl " />
                <div class="flex flex-wrap gap-4 items-center mt-3 mb-4">
                    <select name="role" class="border px-3 py-2 rounded">
                        <option value="">Rôle</option>
                        <?php foreach ($this->roles as $role): ?>
                            <option value="<?= htmlspecialchars($role['nom_role']) ?>" <?= (($_GET['role'] ?? '') == $role['nom_role']) ? 'selected' : '' ?>><?= htmlspecialchars($role['nom_role']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="grade" class="border px-3 py-2 rounded">
                        <option value="">Grade</option>
                        <?php foreach (['Professeur', 'Doctorant', 'Chercheur', 'Autre'] as $grade): ?>
                            <option value="<?= $grade ?>" <?= (($_GET['grade'] ?? '') == $grade) ? 'selected' : '' ?>><?= $grade ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="statut" class="border px-3 py-2 rounded">
                        <option value="">Statut</option>
                        <?php foreach (['actif', 'suspendu', 'inactif'] as $statut): ?>
                            <option value="<?= $statut ?>" <?= (($_GET['statut'] ?? '') == $statut) ? 'selected' : '' ?>>
                                <?= ucfirst($statut) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="id_equipe" class="border px-3 py-2 rounded">
                        <option value="">Équipe</option>
                        <?php foreach ($this->equipes as $equipe): ?>
                            <option value="<?= htmlspecialchars($equipe['id_equipe']) ?>" <?= (($_GET['id_equipe'] ?? '') == $equipe['id_equipe']) ? 'selected' : '' ?>><?= htmlspecialchars($equipe['nom_equipe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="pubs" class="border px-3 py-2 rounded">
                        <option value="">Publications</option>
                        <option value="0" <?= (($_GET['pubs'] ?? '') == '0') ? 'selected' : '' ?>>0</option>
                        <option value="1-5" <?= (($_GET['pubs'] ?? '') == '1-5') ? 'selected' : '' ?>>1-5</option>
                        <option value="6-10" <?= (($_GET['pubs'] ?? '') == '6-10') ? 'selected' : '' ?>>6-10</option>
                        <option value="11+" <?= (($_GET['pubs'] ?? '') == '11+') ? 'selected' : '' ?>>11+</option>
                    </select>
                </div>
                <!-- Ajoutez ici d'autres filtres pertinents selon le SQL: type de projet, etc. -->
                <button type="submit" class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Filtrer</button>
            </form>

            <!-- table des uers -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nom & Prénom</th>
                            <th class="px-4 py-2">Poste</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Rôle</th>
                            <th class="px-4 py-2">Grade</th>
                            <th class="px-4 py-2">Équipe</th>
                            <th class="px-4 py-2">Statut</th>
                            <th class="px-4 py-2">Nb Pubs</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->users as $user): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-center underline text-secondary"><a
                                        href="/admin/users/<?= urlencode($user['id_user']) ?>"><?= htmlspecialchars($user['id_user']) ?></a>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['poste'] ?? '-') ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if (SessionManager::hasPermissions(['roles.read'])): ?>
                                        <a href="/admin/roles/<?= urlencode($user['role']) ?>" class="text-secondary hover:underline">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($user['role']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['grade'] ?? '') ?></td>
                                <td class="px-4 py-2">
                                    <?php
                                    $equipe = $user['equipe'] ?? null;
                                    if (!empty($equipe)) { ?>
                                        <a class="text-secondary underline"
                                        href="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>">
                                            <?= htmlspecialchars($equipe['nom_equipe']) ?>
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-gray-400">-</span>
                                    <?php } ?>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                        <?php
                                        switch ($user['statut']) {
                                            case 'actif':
                                                echo 'bg-green-100 text-green-700';
                                                break;
                                            case 'suspendu':
                                                echo 'bg-yellow-100 text-yellow-700';
                                                break;
                                            case 'inactif':
                                                echo 'bg-red-100 text-red-700';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-500';
                                        }
                                        ?>
                                    ">
                                        <?= htmlspecialchars($user['statut']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center font-bold text-primary"><?= $user['nb_pubs'] ?></td>
                                <td class="px-4 py-2 text-center">
                                    <a href="/admin/users/<?= urlencode($user['id_user']) ?>"
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
