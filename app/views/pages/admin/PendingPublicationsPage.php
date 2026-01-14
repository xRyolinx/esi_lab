<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class PendingPublicationsPage extends AuthTemplate
{
    private $publications;
    public function __construct($title = 'Publications en attente', $data = [])
    {
        parent::__construct($title);
        $this->publications = $data['publications'] ?? [];
    }
    protected function content()
    {
        $publications = $this->publications;
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Publications en attente</h1>
            </div>
            <div class="bg-white rounded shadow p-4">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 text-left">Titre</th>
                            <th class="p-2 text-left">Type</th>
                            <th class="p-2 text-left">Année</th>
                            <th class="p-2 text-left">Auteur(s)</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($publications as $pub): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2 font-semibold">
                                <?= htmlspecialchars($pub['titre']) ?>
                            </td>
                            <td class="p-2"><?= htmlspecialchars($pub['type']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($pub['annee']) ?></td>
                            <td class="p-2">
                                <?php if (!empty($pub['auteurs'])): ?>
                                    <?= htmlspecialchars(implode(', ', array_map(fn($a) => $a['prenom'].' '.$a['nom'], $pub['auteurs']))) ?>
                                <?php endif; ?>
                            </td>
                            <td class="p-2 flex gap-2">
                                <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/accept" method="post" style="display:inline">
                                    <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded">Accepter</button>
                                </form>
                                <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/refuse" method="post" style="display:inline">
                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">Refuser</button>
                                </form>
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
