<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class PublicationsPage extends AuthTemplate
{
    private $publications;
    public function __construct($title = 'Publications', $data = [])
    {
        parent::__construct($title);
        $this->publications = $data['publications'] ?? [];
    }
    protected function content()
    {
        $publications = $this->publications;
        $canWrite = SessionManager::hasPermissions(['publications.write']);
        ?>
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Liste des publications</h1>
                <div class="flex gap-2">
                    <?php if ($canWrite): ?>
                        <a href="/admin/publications/pending"
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">Publications en attente</a>
                    <?php endif; ?>
                    <a href="/admin/publications/new"
                        class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded shadow">Nouvelle publication</a>
                </div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 text-left">Titre</th>
                            <th class="p-2 text-left">Type</th>
                            <th class="p-2 text-left">Année</th>
                            <th class="p-2 text-left">Statut</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($publications)): ?>
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Aucune publication trouvée.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($publications as $pub): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-2 font-semibold">
                                    <a href="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>"
                                        class="text-primary hover:underline">
                                        <?= htmlspecialchars($pub['titre']) ?>
                                    </a>
                                </td>
                                <td class="p-2"><?= htmlspecialchars($pub['type']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($pub['annee']) ?></td>
                                <td class="p-2">
                                    <?php if ($pub['statut'] === 'en_attente'): ?>
                                        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">En attente</span>
                                    <?php elseif ($pub['statut'] === 'valide'): ?>
                                        <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">Acceptée</span>
                                    <?php elseif ($pub['statut'] === 'rejete'): ?>
                                        <span class="bg-red-200 text-red-800 px-2 py-1 rounded text-xs">Refusée</span>
                                    <?php else: ?>
                                        <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs">Autre</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-2">
                                    <a href="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>"
                                        class="text-blue-600 hover:underline">Voir</a>
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
