<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class EquipementsPage extends AuthTemplate
{
    private $equipements;
    public function __construct($title = 'Liste des équipements', array $data = [])
    {
        parent::__construct($title);
        $this->equipements = $data['equipements'] ?? [];
    }
    protected function content()
    {
        $equipements = $this->equipements;
        $canWrite = SessionManager::hasPermissions(['equipements.write']);
        ?>
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Liste des équipements</h1>
                <div class="flex gap-2">
                    <a href="/admin/reservations" class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Historique réservations</a>
                    <?php if ($canWrite): ?>
                        <a href="/admin/equipements/new" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Ajouter un équipement</a>
                    <?php endif; ?>
                </div>
            </div>
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2">Localisation</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($equipements) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">Aucun équipement trouvé.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($equipements as $e): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">
                                <a class="text-secondary underline" href="/admin/equipements/<?= urlencode($e['id_equipement']) ?>">
                                    <?= htmlspecialchars($e['id_equipement']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($e['nom']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($e['type']) ?></td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-1 rounded text-xs <?php
                                    switch ($e['statut']) {
                                        case 'disponible': echo 'bg-green-100 text-green-800'; break;
                                        case 'reserve': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'maintenance': echo 'bg-red-100 text-red-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                ?>">
                                    <?= htmlspecialchars($e['statut']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($e['localisation']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="/admin/equipements/<?= urlencode($e['id_equipement']) ?>" class="text-primary hover:underline">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
