<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../models/Users.php';
require_once __DIR__ . '/../../../models/PublicationAuteur.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SingleEquipePage extends AuthTemplate
{
    private $equipe;
    public function __construct($title = 'Détail équipe', array $data = [])
    {
        parent::__construct($title);
        $this->equipe = $data['equipe'] ?? [];
    }

    protected function content()
    {
        $equipe = $this->equipe;
        $membres = $equipe['membres'] ?? [];
        $ressources = $equipe['ressources'] ?? [];
        $nb_pubs = $equipe['nb_pubs'] ?? 0;
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Équipe : <?= htmlspecialchars($equipe['nom_equipe']) ?></h1>
                <?php if (SessionManager::hasPermissions(['equipes.write'])): ?>
                    <button onclick="document.getElementById('editEquipeForm').classList.toggle('hidden')"
                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Editer</button>
                <?php endif; ?>
            </div>
            <div class="mb-4 text-gray-700"><strong>Description :</strong>
                <?= nl2br(htmlspecialchars($equipe['description'])) ?></div>
            <div class="mb-4"><strong>Date de création :</strong> <?= htmlspecialchars($equipe['date_creation']) ?></div>
            <div class="mb-4"><strong>Nombre de publications :</strong> <?= $nb_pubs ?></div>
            <div class="mb-4"><strong>Ressources allouées :</strong> <?= !empty($ressources) ? count($ressources) : 'Aucune' ?>
            </div>

            <!-- Formulaire édition équipe -->
            <?php if (SessionManager::hasPermissions(['equipes.write'])): ?>
                <form id="editEquipeForm" class="hidden mb-8" method="POST"
                    action="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>">
                    <input type="hidden" name="_method" value="PUT">
                    <h2 class="text-xl font-semibold mt-8 mb-2">Modifier les informations de l'équipe</h2>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="nom_equipe">Nom de l'équipe</label>
                        <input type="text" name="nom_equipe" id="nom_equipe" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($equipe['nom_equipe']) ?>" required />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="description">Description</label>
                        <textarea name="description" id="description" class="w-full border px-3 py-2 rounded" rows="3"
                            required><?= htmlspecialchars($equipe['description']) ?></textarea>
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Enregistrer</button>
                        <button type="button" onclick="document.getElementById('editEquipeForm').classList.add('hidden')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Annuler</button>
                    </div>
                </form>
            <?php endif; ?>


            <h2 class="text-xl font-semibold mt-8 mb-2">Membres de l'équipe</h2>
            <!-- set chef -->
            <form method="POST" action="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>/set-chef"
                class="mb-4 flex gap-2 items-center">
                <label for="id_chef" class="font-medium">Chef d'équipe :</label>
                <select <?= SessionManager::hasPermissions(['equipes.write']) ? '' : 'disabled' ?> name="id_chef" id="id_chef"
                    class="border px-3 py-2 rounded" required>
                    <!-- default option -->
                    <option value="">
                        <?= SessionManager::hasPermissions(['equipes.write'])
                            ? 'Sélectionner le chef' : '-' ?>
                    </option>

                    <!-- autres membres -->
                    <?php foreach ($membres as $m): ?>
                        <option value="<?= $m['id_user'] ?>" <?= ($equipe['id_chef'] ?? null) == $m['id_user'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (SessionManager::hasPermissions(['equipes.write'])): ?>
                    <button type="submit" class="bg-secondary text-white px-3 py-2 rounded hover:bg-secondary-dark">Définir</button>
                <?php endif; ?>
            </form>

            <!-- liste membres -->
            <table class="min-w-full bg-white rounded shadow mb-6">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Prénom</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Grade</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($membres) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">Aucun membre dans cette équipe.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($membres as $m): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">
                                <?php if (SessionManager::hasPermissions(['users.read'])): ?>
                                    <a class="text-secondary underline"
                                        href="/admin/users/<?= urlencode($m['id_user']) ?>"><?= htmlspecialchars($m['id_user']) ?></a>
                                <?php else: ?>
                                    <?= htmlspecialchars($m['id_user']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($m['nom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($m['prenom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($m['email']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($m['grade']) ?></td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                <?php if (SessionManager::hasPermissions(['users.read'])): ?>
                                    <a href="/admin/users/<?= urlencode($m['id_user']) ?>" class="text-primary hover:underline">Voir</a>
                                <?php endif; ?>
                                <?php if (SessionManager::hasPermissions(['equipes.write'])): ?>
                                    <form method="POST" action="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>/remove-user"
                                        style="display:inline" onsubmit="return confirm('Retirer ce membre ?');">
                                        <input type="hidden" name="id_user" value="<?= $m['id_user'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Retirer"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if (!SessionManager::hasPermissions(['users.read']) && !SessionManager::hasPermissions(['equipes.write'])): ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- add membre -->
            <?php if (SessionManager::hasPermissions(['equipes.write'])): ?>
                <h2 class="text-xl font-semibold mt-8 mb-2">Ajouter un membre sans équipe</h2>
                <form method="POST" action="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>/add-user"
                    class="flex gap-2 items-center mb-8">
                    <select name="id_user" class="border px-3 py-2 rounded" required>
                        <option value="">Sélectionner un utilisateur</option>
                        <?php foreach (Users::getAll([], [], []) as $u):
                            if (empty($u['id_equipe'])): ?>
                                <option value="<?= $u['id_user'] ?>">
                                    <?= htmlspecialchars($u['nom'] . ' ' . $u['prenom'] . ' (' . $u['email'] . ')') ?>
                                </option>
                            <?php endif; endforeach; ?>
                    </select>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Ajouter</button>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }
}
