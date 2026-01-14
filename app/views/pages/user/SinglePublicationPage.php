<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class SinglePublicationPage extends MainTemplate
{
    private array $publication;

    public function __construct($title = 'Détail publication', array $data = [])
    {
        parent::__construct($title);
        $this->publication = $data['publication'] ?? [];
    }

    protected function content()
    {
        $pub = $this->publication;
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Publication : <?= htmlspecialchars($pub['titre']) ?></h1>
            <div class="mb-4 text-gray-700">
                <strong>Type :</strong> <?= htmlspecialchars($pub['type'] ?? '-') ?><br>
                <strong>Domaine :</strong> <?= htmlspecialchars($pub['domaine'] ?? '-') ?><br>
                <strong>Doi :</strong> <?= htmlspecialchars($pub['doi'] ?? '-') ?><br>
                <strong>Année :</strong> <?= htmlspecialchars($pub['annee'] ?? '-') ?><br>
                <strong>Résumé :</strong> <?= nl2br(htmlspecialchars($pub['resume'] ?? '')) ?><br>
            </div>
            <div class="mb-4">
                <?php if (!empty($pub['lien_telechargement'])): ?>
                    <a href="<?= htmlspecialchars($pub['lien_telechargement']) ?>" download
                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark font-bold">
                        Télécharger la publication
                    </a>
                <?php endif; ?>
            </div>
            <!-- auteurs -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Auteurs</h2>
                <ul class="list-disc ml-8">
                    <?php foreach ($pub['auteurs'] ?? [] as $auteur): ?>
                        <li>
                            <a href="/users/<?= urlencode($auteur['id_user']) ?>" class="text-primary underline">
                                <?= htmlspecialchars($auteur['prenom'] . ' ' . $auteur['nom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- projets -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Projets associés</h2>
                <ul class="list-disc ml-8">
                    <?php foreach ($pub['projets'] ?? [] as $projet): ?>
                        <li>
                            <a href=<?= "/projets/" . urlencode($projet['id_projet']) ?> class="text-primary underline">
                                <?= htmlspecialchars($projet['titre']) ?> (<?= htmlspecialchars($projet['date_debut']) ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
    }
}
