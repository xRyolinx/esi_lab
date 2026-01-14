<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class PublicationsPage extends MainTemplate
{
    private array $publications;

    public function __construct($title = 'Liste des publications', array $data = [])
    {
        parent::__construct($title);
        $this->publications = $data['publications'] ?? [];
    }

    protected function content()
    {
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Liste des publications</h1>
            <!-- Filtres -->
            <form method="GET" class="mb-8 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="type" name="type" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <div>
                    <label for="domaine" class="block text-sm font-medium text-gray-700">Domaine</label>
                    <input type="text" id="domaine" name="domaine" value="<?= htmlspecialchars($_GET['domaine'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <div>
                    <label for="annee" class="block text-sm font-medium text-gray-700">Année</label>
                    <input type="number" min="1900" max="2099" step="1" id="annee" name="annee" value="<?= htmlspecialchars($_GET['annee'] ?? '') ?>" class="border rounded px-2 py-1 w-40" />
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Filtrer</button>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($this->publications as $pub): ?>
                    <div class="bg-white rounded shadow p-4 flex flex-col justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-primary mb-2">
                                <a href="/publications/<?= urlencode($pub['id_publication']) ?>" class="underline">
                                    <?= htmlspecialchars($pub['titre']) ?>
                                </a>
                            </h2>
                            <div class="text-sm text-gray-500 mb-1">Type : <?= htmlspecialchars($pub['type']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Domaine : <?= htmlspecialchars($pub['domaine']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Année : <?= htmlspecialchars($pub['annee']) ?></div>
                            <div class="text-sm text-gray-500 mb-1">Projet :
                                <?php foreach ($pub['projets'] ?? [] as $projet): ?>
                                    <span class="font-semibold text-secondary mr-2"><?= htmlspecialchars($projet['titre']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-600">Auteurs :</span>
                            <ul class="list-disc ml-4">
                                <?php foreach ($pub['auteurs'] ?? [] as $auteur): ?>
                                    <li>
                                        <a href="/users/<?= urlencode($auteur['id_user']) ?>" class="text-primary underline">
                                            <?= htmlspecialchars($auteur['prenom'] . ' ' . $auteur['nom']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
