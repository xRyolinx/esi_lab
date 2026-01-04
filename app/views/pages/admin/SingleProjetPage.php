<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';
class SingleProjetPage extends AuthTemplate
{
    private $projet;
    private $users_disponibles;
    private $partenaires_disponibles;
    public function __construct($title, array $data)
    {
        parent::__construct($title);
        $this->projet = $data['projet'] ?? [];
        $this->users_disponibles = $data['users_disponibles'] ?? [];
        $this->partenaires_disponibles = $data['partenaires_disponibles'] ?? [];
    }

    public function content()
    {
        $projet = $this->projet;
        $id_responsable = $projet['id_responsable'] ?? null;
        $users = $projet['users'] ?? [];#
        $partenaires = $projet['partenaires'] ?? [];

        $users_disponibles = $this->users_disponibles;
        $partenaires_disponibles = $this->partenaires_disponibles;

        $en_cours = $projet['statut'] === 'en_cours';
        $canWrite = SessionManager::hasPermissions(['projets.write']);

        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Projet : <?= htmlspecialchars($projet['titre']) ?></h1>
                <?php if ($canWrite): ?>
                    <div class="flex gap-2">
                        <?php if ($projet['statut'] === 'en_cours'): ?>
                            <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/cloturer"
                                onsubmit="return confirm('Clôturer ce projet ? Cette action est irréversible.');">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Clôturer</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/reouvrir"
                                onsubmit="return confirm('Remettre ce projet en cours ?');">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Rouvrir</button>
                            </form>
                        <?php endif; ?>
                        <button onclick="document.getElementById('editProjetForm').classList.toggle('hidden')"
                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Editer</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4 text-gray-700"><strong>Description :</strong>
                <?= nl2br(htmlspecialchars($projet['description'])) ?></div>
            <div class="mb-4"><strong>Thématique :</strong> <?= htmlspecialchars($projet['thematique']) ?></div>
            <div class="mb-4"><strong>Type de financement :</strong> <?= htmlspecialchars($projet['type_financement']) ?></div>
            <div class="mb-4"><strong>Date début :</strong> <?= htmlspecialchars($projet['date_debut']) ?></div>
            <div class="mb-4"><strong>Date fin :</strong>
                <?php if ($projet['date_fin'] != '0000-00-00'): 
                    echo htmlspecialchars($projet['date_fin']);
                else:
                    echo '/';
                endif; ?>
            </div>
            <div class="mb-4"><strong>Statut :</strong> <?= htmlspecialchars($projet['statut']) ?></div>
            <div class="mb-4 flex items-center gap-4">
                <strong>Responsable :</strong>

                <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/set-responsable"
                    class="flex items-center gap-2">
                    <select <?= $en_cours ? '' : 'disabled' ?> name="id_responsable" class="border px-3 py-2 rounded"
                        <?= $canWrite ? '' : 'disabled' ?>>
                        <option value="">Aucun</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id_user'] ?>" <?= ($id_responsable && $id_responsable == $u['id_user'])
                                  ? 'selected' : ''
                                  ?>>
                                <?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($canWrite && $en_cours): ?>
                        <button type="submit" class="bg-primary text-white px-3 py-2 rounded hover:bg-primary-dark">Définir</button>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Formulaire édition projet -->
            <?php if ($canWrite): ?>
                <form id="editProjetForm" class="hidden mb-8" method="POST"
                    action="/admin/projets/<?= urlencode($projet['id_projet']) ?>">
                    <input type="hidden" name="_method" value="PUT">
                    <h2 class="text-xl font-semibold mt-8 mb-2">Modifier les informations du projet</h2>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="titre">Titre</label>
                        <input type="text" name="titre" id="titre" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($projet['titre']) ?>" required />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="description">Description</label>
                        <textarea name="description" id="description" class="w-full border px-3 py-2 rounded" rows="3"
                            required><?= htmlspecialchars($projet['description']) ?></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="thematique">Thématique</label>
                        <input type="text" name="thematique" id="thematique" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($projet['thematique']) ?>" />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="type_financement">Type de financement</label>
                        <input type="text" name="type_financement" id="type_financement" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($projet['type_financement']) ?>" />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="date_debut">Date début</label>
                        <input type="date" name="date_debut" id="date_debut" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($projet['date_debut']) ?>" required />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="date_fin">Date fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="w-full border px-3 py-2 rounded"
                            value="<?= htmlspecialchars($projet['date_fin']) ?>" />
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="statut">Statut</label>
                        <select name="statut" id="statut" class="w-full border px-3 py-2 rounded">
                            <option value="en_cours" <?= $projet['statut'] == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="termine" <?= $projet['statut'] == 'termine' ? 'selected' : '' ?>>Terminé</option>
                        </select>
                    </div>
                    <!-- champ responsable retiré du formulaire d'édition -->
                    <div>
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Enregistrer</button>
                        <button type="button" onclick="document.getElementById('editProjetForm').classList.add('hidden')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Annuler</button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- Section membres (users) -->
            <h2 class="text-xl font-semibold mt-8 mb-2">Membres du projet</h2>
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
                    <?php if (count($users) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">Aucun membre dans ce projet.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($users as $u): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">
                                <?php if (SessionManager::hasPermissions(['users.read'])): ?>
                                    <a class="text-secondary underline"
                                        href="/admin/users/<?= urlencode($u['id_user']) ?>"><?= htmlspecialchars($u['id_user']) ?></a>
                                <?php else: ?>
                                    <?= htmlspecialchars($u['id_user']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($u['nom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($u['prenom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($u['grade']) ?></td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                <?php if (SessionManager::hasPermissions(['users.read'])): ?>
                                    <a href="/admin/users/<?= urlencode($u['id_user']) ?>" class="text-primary hover:underline">Voir</a>
                                <?php endif; ?>
                                <?php if ($canWrite && $en_cours): ?>
                                    <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/remove-user"
                                        style="display:inline" onsubmit="return confirm('Retirer ce membre ?');">
                                        <input type="hidden" name="id_user" value="<?= $u['id_user'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Retirer"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if (!SessionManager::hasPermissions(['users.read']) && !$canWrite): ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- add membre -->
            <?php if ($canWrite && $en_cours): ?>
                <h2 class="text-xl font-semibold mt-8 mb-2">Ajouter un membre</h2>
                <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/add-user"
                    class="flex gap-2 items-center mb-8">
                    <select name="id_user" class="border px-3 py-2 rounded" required>
                        <option value="">Sélectionner un utilisateur</option>
                        <?php foreach ($users_disponibles as $u): ?>
                            <option value="<?= $u['id_user'] ?>">
                                <?= htmlspecialchars($u['nom'] . ' ' . $u['prenom'] . ' (' . $u['email'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Ajouter</button>
                </form>
            <?php endif; ?>

            <!-- Section partenaires -->
            <h2 class="text-xl font-semibold mt-8 mb-2">Partenaires du projet</h2>
            <table class="min-w-full bg-white rounded shadow mb-6">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Site web</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($partenaires) === 0): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">Aucun partenaire dans ce projet.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($partenaires as $p): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">
                                <?php if (SessionManager::hasPermissions(['partenaires.read'])): ?>
                                    <a class="text-secondary underline"
                                        href="/admin/partenaires/<?= urlencode($p['id_partenaire']) ?>"><?= htmlspecialchars($p['id_partenaire']) ?></a>
                                <?php else: ?>
                                    <?= htmlspecialchars($p['id_partenaire']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($p['nom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($p['type']) ?></td>
                            <td class="px-4 py-2">
                                <?php if ($p['site_web'] == null || $p['site_web'] == ''): ?>
                                    N/A
                                <?php else: ?>
                                    <a href="<?= htmlspecialchars($p['site_web']) ?>" target="_blank"
                                        class="text-blue-600 underline">Site</a>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                <?php if (SessionManager::hasPermissions(['partenaires.read'])): ?>
                                    <a href="/admin/partenaires/<?= urlencode($p['id_partenaire']) ?>"
                                        class="text-primary hover:underline">Voir</a>
                                <?php endif; ?>
                                <?php if ($canWrite && $en_cours): ?>
                                    <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/remove-partenaire"
                                        style="display:inline" onsubmit="return confirm('Retirer ce partenaire ?');">
                                        <input type="hidden" name="id_partenaire" value="<?= $p['id_partenaire'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Retirer"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if (!SessionManager::hasPermissions(['partenaires.read']) && !$canWrite): ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- add partenaire -->
            <?php if ($canWrite && $en_cours): ?>
                <h2 class="text-xl font-semibold mt-8 mb-2">Ajouter un partenaire</h2>
                <form method="POST" action="/admin/projets/<?= urlencode($projet['id_projet']) ?>/add-partenaire"
                    class="flex gap-2 items-center mb-8">
                    <select name="id_partenaire" class="border px-3 py-2 rounded" required>
                        <option value="">Sélectionner un partenaire</option>
                        <?php foreach ($partenaires_disponibles as $p): ?>
                            <option value="<?= $p['id_partenaire'] ?>">
                                <?= htmlspecialchars($p['nom'] . ' (' . $p['type'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Ajouter</button>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }
}
