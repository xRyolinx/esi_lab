<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/Card.php';

class EvenementsPage extends MainTemplate
{
    private array $evenements;
    private bool $isLoggedIn;

    public function __construct($title = 'Événements', array $data = [])
    {
        parent::__construct($title);
        $this->evenements = $data['evenements'] ?? [];
        $this->isLoggedIn = $data['isLoggedIn'] ?? false;
    }

    protected function content()
    {
        $isLoggedIn = $this->isLoggedIn;
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Événements</h1>
            <form method="GET" class="mb-8 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="type" name="type" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <?php if ($isLoggedIn): ?>
                <div>
                    <label for="isPublic" class="block text-sm font-medium text-gray-700">Visibilité</label>
                    <select id="isPublic" name="isPublic" class="border rounded px-2 py-1 w-40">
                        <option value="">Tous</option>
                        <option value="1" <?= ($_GET['isPublic'] ?? '') === '1' ? 'selected' : '' ?>>Public</option>
                        <option value="0" <?= ($_GET['isPublic'] ?? '') === '0' ? 'selected' : '' ?>>Privé</option>
                    </select>
                </div>
                <?php endif; ?>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Filtrer</button>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($this->evenements as $idx => $event): ?>
                    <?php
                        ob_start();
                        ?>
                        <p class="text-gray-600 mb-4">
                            <?= htmlspecialchars($event['lieu'] ?? '') ?>
                            <?php if (!empty($event['date_debut'])): ?>
                                <br><span class="text-xs text-blue-700 font-semibold">Le
                                    <?= htmlspecialchars($event['date_debut']) ?><?php if (!empty($event['date_fin'])): ?> au
                                        <?= htmlspecialchars($event['date_fin']) ?><?php endif; ?></span>
                            <?php endif; ?>
                        </p>
                        <?php
                        $content = ob_get_clean();
                        $footer = '<a href="/evenements/' . urlencode($event['id_evenement']) . '" class="text-secondary font-semibold hover:underline">Voir plus →</a>';
                        $card = new Card(
                            $event['titre'],
                            $content,
                            $footer,
                            [
                                'class' => 'event-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between h-full',
                            ]
                        );
                        $card->render();
                    ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
