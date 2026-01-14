<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class ProjetsPage extends MainTemplate
{
    private array $projets;

    public function __construct($title = 'Liste des projets', array $data = [])
    {
        parent::__construct($title);
        $this->projets = $data['projets'] ?? [];
    }

    protected function content()
    {
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Liste des projets</h1>
            <!-- Filtres -->
            <form method="GET" class="mb-8 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="thematique" class="block text-sm font-medium text-gray-700">Thématique</label>
                    <input type="text" id="thematique" name="thematique" value="<?= htmlspecialchars($_GET['thematique'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <div>
                    <label for="financement" class="block text-sm font-medium text-gray-700">Financement</label>
                    <input type="text" id="financement" name="financement" value="<?= htmlspecialchars($_GET['financement'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select id="statut" name="statut" class="border rounded px-2 py-1 w-40">
                        <option value="">Tous</option>
                        <option value="en_cours" <?= ($_GET['statut'] ?? '') === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="termine" <?= ($_GET['statut'] ?? '') === 'termine' ? 'selected' : '' ?>>Terminé</option>
                    </select>
                </div>
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Filtrer</button>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($this->projets as $projet): ?>
                    <div class="bg-white rounded shadow p-4 flex flex-col justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-primary mb-2">
                                <a href="/projets/<?= urlencode($projet['id_projet']) ?>" class="underline">
                                    <?= htmlspecialchars($projet['titre']) ?>
                                </a>
                            </h2>
                            <div class="text-sm text-gray-500 mb-1">Thématique : <?= htmlspecialchars($projet['thematique']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Financement : <?= htmlspecialchars($projet['type_financement']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Statut : <?= htmlspecialchars($projet['statut']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Date début : <?= htmlspecialchars($projet['date_debut']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
