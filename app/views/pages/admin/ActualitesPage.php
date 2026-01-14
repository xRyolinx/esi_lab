<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class ActualitesPage extends AuthTemplate
{
    private $actualites;
    public function __construct($title = 'Gestion des actualités', array $data = [])
    {
        parent::__construct($title);
        $this->actualites = $data['actualites'] ?? [];
    }
    protected function content()
    {
        $canWrite = SessionManager::hasPermissions(['actualites.write']);
        // Collect unique types for filter
        $types = array_unique(array_map(function($a) { return $a['type']; }, $this->actualites));
        sort($types);
        $selectedType = $_GET['type'] ?? '';
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Actualités</h1>
                <?php if ($canWrite): ?>
                    <a href="/admin/actualites/new"
                        class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Ajouter actualité
                    </a>
                <?php endif; ?>
            </div>

            <!-- Filtres -->
            <form method="GET" class="mb-6 flex flex-wrap gap-4 items-center">
                <select name="type" class="border px-3 py-2 rounded">
                    <option value="">Type</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= ($selectedType == $type) ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Filtrer</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Titre</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Date publication</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->actualites as $a):
                            if ($selectedType && $a['type'] !== $selectedType) continue;
                        ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center font-bold text-secondary underline">
                                <a href="/admin/actualites/<?= urlencode($a['id_actualite']) ?>">
                                    <?= htmlspecialchars($a['id_actualite']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-2 font-semibold">
                                <?= htmlspecialchars($a['titre']) ?>
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                    <?= htmlspecialchars($a['type']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <?= htmlspecialchars($a['date_publication']) ?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="/admin/actualites/<?= urlencode($a['id_actualite']) ?>"
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
