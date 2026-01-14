<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class SingleProjetPage extends MainTemplate
{
    private array $projet;

    public function __construct($title = 'Détail du projet', array $data = [])
    {
        parent::__construct($title);
        $this->projet = $data['projet'] ?? [];
    }

    protected function content()
    {
        $chef = $this->projet['chef'] ?? null;
        $membres = $this->projet['membres'] ?? [];
        $partenaires = $this->projet['partenaires'] ?? [];
        $publications = $this->projet['publications'] ?? [];
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Projet : <?= htmlspecialchars($this->projet['titre'] ?? '') ?></h1>
            <div class="mb-6">
                <div class="text-lg font-semibold">Chef du projet :</div>
                <?php if ($chef): ?>
                    <a href="/users/<?= urlencode($chef['id_user']) ?>" class="text-primary underline">
                        <?= htmlspecialchars($chef['prenom'] . ' ' . $chef['nom']) ?>
                    </a>
                <?php else: ?>
                    <span class="text-gray-500">Non défini</span>
                <?php endif; ?>
            </div>
            <div class="mb-6">
                <div class="text-lg font-semibold">Membres :</div>
                <ul class="list-disc ml-4">
                    <?php foreach ($membres as $membre): ?>
                        <li>
                            <a href="/users/<?= urlencode($membre['id_user']) ?>" class="text-primary underline">
                                <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mb-6">
                <div class="text-lg font-semibold">Partenaires :</div>
                <ul class="list-disc ml-4">
                    <?php foreach ($partenaires as $partenaire): ?>
                        <li><?= htmlspecialchars($partenaire['nom']) ?> (<?= htmlspecialchars($partenaire['type']) ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mb-6">
                <div class="text-lg font-semibold">Publications des membres :</div>
                <ul class="list-disc ml-4">
                    <?php foreach ($publications as $pub): ?>
                        <li>
                            <a href="/publications/<?= urlencode($pub['id_publication']) ?>" class="text-primary underline">
                                <?= htmlspecialchars($pub['titre']) ?> (<?= htmlspecialchars($pub['annee']) ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
    }
}
