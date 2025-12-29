<?php

require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class ProjetsPage extends AuthTemplate
{
    private $projets;
    public function __construct($title = 'Gestion des projets', array $data = [])
    {
        parent::__construct($title);
        $this->projets = $data['projets'] ?? [];
    }
    protected function content()
    {
        $canWrite = SessionManager::hasPermissions(['projets.write']);
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Projets</h1>
                <div class="flex gap-2">
                    <?php if ($canWrite): ?>
                        <a href="/admin/projets/new"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> Nouveau projet
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Titre</th>
                            <th class="px-4 py-2">Thématique</th>
                            <th class="px-4 py-2">Type financement</th>
                            <th class="px-4 py-2">Date début</th>
                            <th class="px-4 py-2">Date fin</th>
                            <th class="px-4 py-2">Statut</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($this->projets) == 0): ?>
                            <tr>
                                <td class="px-4 py-2 text-center" colspan="8">Aucun projet trouvé.</td>
                            </tr>
                        <?php endif; ?>
                            
                        <?php foreach ($this->projets as $projet): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-center underline text-secondary"><a
                                        href="/admin/projets/<?= urlencode($projet['id_projet']) ?>"><?= htmlspecialchars($projet['id_projet']) ?></a>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($projet['titre']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($projet['thematique']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($projet['type_financement']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($projet['date_debut'] == '0000-00-00' ? '-' : $projet['date_debut']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($projet['date_fin'] == '0000-00-00' ? '-' : $projet['date_fin']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if ($projet['statut'] === 'en_cours'): ?>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">En cours</span>
                                    <?php elseif ($projet['statut'] === 'termine'): ?>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Terminé</span>
                                    <?php elseif ($projet['statut'] === 'soumis'): ?>
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Soumis</span>
                                    <?php else: ?>
                                        <span
                                            class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs"><?= htmlspecialchars($projet['statut']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="/admin/projets/<?= urlencode($projet['id_projet']) ?>"
                                        class="text-primary hover:underline font-semibold">Voir</a>
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
