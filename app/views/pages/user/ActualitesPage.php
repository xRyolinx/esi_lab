<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/Card.php';

class ActualitesPage extends MainTemplate
{
    private array $actualites;

    public function __construct($title = 'Actualités', array $data = [])
    {
        parent::__construct($title);
        $this->actualites = $data['actualites'] ?? [];
    }

    protected function content()
    {
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Actualités</h1>
            <form method="GET" class="mb-8 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="type" name="type" class="border rounded px-2 py-1 w-40">
                        <option value="">Tous</option>
                        <option value="projet" <?= ($_GET['type'] ?? '') === 'projet' ? 'selected' : '' ?>>Projet</option>
                        <option value="publication" <?= ($_GET['type'] ?? '') === 'publication' ? 'selected' : '' ?>>Publication</option>
                        <option value="evenement" <?= ($_GET['type'] ?? '') === 'evenement' ? 'selected' : '' ?>>Événement</option>
                        <option value="soutenance" <?= ($_GET['type'] ?? '') === 'soutenance' ? 'selected' : '' ?>>Soutenance</option>
                    </select>
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Filtrer</button>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($this->actualites as $actu): ?>
                    <?php
                        $content = '<div class="text-sm text-gray-500 mb-1">Type : ' . htmlspecialchars($actu['type']) . '</div>';
                        $content .= '<div class="mb-2">' . nl2br(htmlspecialchars($actu['description'])) . '</div>';
                        $content .= '<div class="text-xs text-gray-400">' . date('d/m/Y', strtotime($actu['date_publication'])) . '</div>';
                        (new Card(
                            $actu['titre'],
                            $content
                        ))->render();
                    ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
