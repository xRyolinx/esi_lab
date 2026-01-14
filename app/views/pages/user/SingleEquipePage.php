<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class SingleEquipePage extends MainTemplate
{
    private array $equipe;

    public function __construct($title = 'Détail équipe', array $data = [])
    {
        parent::__construct($title);
        $this->equipe = $data['equipe'] ?? [];
    }

    protected function content()
    {
        $equipe = $this->equipe;
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Equipe : <?= htmlspecialchars($equipe['nom_equipe']) ?></h1>
            <div class="mb-4 text-gray-700">
                <strong>Description :</strong> <?= nl2br(htmlspecialchars($equipe['description'] ?? '')) ?>
            </div>
            <?php $chef = $equipe['chef'] ?? null;
            if ($chef): ?>
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Chef d'équipe</h2>
                    <div class="flex flex-col items-center mb-2">
                        <img src="<?= htmlspecialchars($chef['photo']) ?>" alt="photo chef"
                            class="w-16 h-16 rounded-full border border-primary mb-2">
                        <span
                            class="font-bold text-lg text-primary"><?= htmlspecialchars($chef['prenom'] . ' ' . $chef['nom']) ?></span>
                        <span class="text-sm text-gray-500">Poste :
                            <?= htmlspecialchars($chef['poste'] ?? 'Chef d\'équipe') ?></span>
                        <span class="text-sm text-gray-500">Grade : <?= htmlspecialchars($chef['grade']) ?></span>
                        <a href="/users/<?= urlencode($chef['id_user']) ?>" class="text-secondary underline">Biographie &
                            publications</a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Membres</h2>
                <div class="flex flex-wrap gap-6">
                    <?php foreach ($equipe['membres'] ?? [] as $user): ?>
                        <div class="flex flex-col items-center">
                            <img src="<?= htmlspecialchars($user['photo']) ?>" alt="photo membre"
                                class="w-12 h-12 rounded-full border border-gray-300 mb-1">
                            <span
                                class="text-base font-bold text-primary"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                            <span class="text-sm text-gray-500">Poste : <?= htmlspecialchars($user['poste'] ?? '-') ?></span>
                            <span class="text-sm text-gray-500">Grade : <?= htmlspecialchars($user['grade']) ?></span>
                            <a href="/users/<?= urlencode($user['id_user']) ?>" class="text-secondary underline">Biographie &
                                publications</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Publications de l'équipe</h2>
                <ul class="list-disc ml-8">
                    <?php foreach ($equipe['publications'] ?? [] as $pub): ?>
                        <li>
                            <a href="/publications/<?= $pub['id_publication'];?>"
                            class="underline">
                                <span class="font-bold text-primary"><?= htmlspecialchars($pub['titre']) ?></span>
                                <span class="text-sm text-gray-500">(<?= htmlspecialchars($pub['annee']) ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
    }
}
