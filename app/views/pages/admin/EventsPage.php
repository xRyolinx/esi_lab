
<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class EventsPage extends AuthTemplate
{
    private $events;
    public function __construct($title = 'Gestion des événements', array $data = [])
    {
        parent::__construct($title);
        $this->events = $data['events'] ?? [];
    }
    protected function content()
    {
        $canWrite = SessionManager::hasPermissions(['events.write']);
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Événements</h1>
                <?php if ($canWrite): ?>
                    <a href="/admin/evenements/new"
                        class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Ajouter événement
                    </a>
                <?php endif; ?>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Titre</th>
                            <th class="px-4 py-2">Public</th>
                            <th class="px-4 py-2">Date début</th>
                            <th class="px-4 py-2">Date fin</th>
                            <th class="px-4 py-2">Lieu</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($this->events) === 0): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Aucun événement trouvé.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($this->events as $event): ?>
                        <tr>
                            <td class="px-4 py-2 text-center text-secondary underline"><a href="/admin/evenements/<?= urlencode($event['id_evenement']) ?>"><?= htmlspecialchars($event['id_evenement']) ?></a></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($event['titre']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <input type="checkbox" disabled <?= $event['isPublic'] ? 'checked' : '' ?> name="" id="">
                            </td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($event['date_debut']) ?></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($event['date_fin']) ?></td>
                            <td class="px-4 py-2 text-center"><?= htmlspecialchars($event['lieu']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="/admin/evenements/<?= $event['id_evenement'] ?>"
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
