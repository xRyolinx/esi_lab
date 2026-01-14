<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class SinglePublicationPage extends AuthTemplate
{
    private $publication;
    public function __construct($title = 'Publication', $data = [])
    {
        parent::__construct($title);
        $this->publication = $data['publication'] ?? null;
    }
    protected function content()
    {
        $pub = $this->publication;
        $canEdit = SessionManager::hasPermissions(['publications.write']);

        if (!$pub) {
            echo '<h2>Publication introuvable.</h2>';
            return;
        }
        ?>
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8 relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-1"><?= htmlspecialchars($pub['titre']) ?></h1>
                    <div class="text-gray-500 text-lg mb-2">Type : <?= htmlspecialchars($pub['type']) ?> | Année : <?= htmlspecialchars($pub['annee']) ?></div>
                    <span class="inline-block bg-primary text-white text-xs px-3 py-1 rounded-full mr-2">Statut :
                        <?php if ($pub['statut'] === 'en_attente'): ?><span>En attente</span>
                        <?php elseif ($pub['statut'] === 'valide'): ?>
                            <span>Acceptée</span>
                        <?php elseif ($pub['statut'] === 'rejete'): ?>
                            <span>Refusée</span>
                        <?php else: ?>
                            <span>Autre</span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($canEdit): ?>
                    <a href="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/edit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-secondary text-white rounded-lg shadow transition">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Résumé :</span>
                <div class="bg-gray-100 rounded p-3 text-gray-700 mt-1 mb-2"><?= nl2br(htmlspecialchars($pub['resume'])) ?></div>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Domaine :</span> <?= htmlspecialchars($pub['domaine']) ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Date de publication :</span> <?= htmlspecialchars($pub['date_publication']) ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">DOI :</span> <?= htmlspecialchars($pub['doi']) ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Auteur(s) :</span>
                <?php if (!empty($pub['auteurs'])): ?>
                    <?= htmlspecialchars(implode(', ', array_map(fn($a) => $a['prenom'].' '.$a['nom'], $pub['auteurs']))) ?>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Projet(s) :</span>
                <?php if (!empty($pub['projets'])): ?>
                    <?= htmlspecialchars(implode(', ', array_map(fn($p) => $p['titre'].'#'.$p['id_projet'], $pub['projets']))) ?>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <span class="font-semibold">Fichier :</span>
                <?php if (!empty($pub['lien_telechargement'])): ?>
                    <a href="<?= htmlspecialchars($pub['lien_telechargement']) ?>" class="text-blue-600 hover:underline" download>Télécharger</a>
                <?php else: ?>
                    <span class="text-gray-500">Aucun fichier</span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
