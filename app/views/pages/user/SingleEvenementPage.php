<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class SingleEvenementPage extends MainTemplate
{
    private array $evenement;
    private bool $isInscrit;
    private bool $isLoggedIn;

    public function __construct($title = 'Détail événement', array $data = [])
    {
        parent::__construct($title);
        $this->evenement = $data['evenement'] ?? [];
        $this->isInscrit = $data['isInscrit'] ?? false;
        $this->isLoggedIn = SessionManager::isLoggedIn();
    }

    protected function content()
    {
        $event = $this->evenement;
        ?>
        <section class="container mx-auto px-4 py-12 max-w-2xl">
            <h1 class="text-3xl font-bold text-primary mb-6">Événement : <?= htmlspecialchars($event['titre'] ?? '') ?></h1>
            <div class="mb-4">
                <div class="text-lg font-semibold">Type :</div>
                <div><?= htmlspecialchars($event['type'] ?? '') ?></div>
            </div>
            <div class="mb-4">
                <div class="text-lg font-semibold">Description :</div>
                <div><?= nl2br(htmlspecialchars($event['description'] ?? '')) ?></div>
            </div>
            <div class="mb-4">
                <div class="text-lg font-semibold">Lieu :</div>
                <div><?= htmlspecialchars($event['lieu'] ?? '') ?></div>
            </div>
            <div class="mb-4">
                <div class="text-lg font-semibold">Début :</div>
                <div><?= htmlspecialchars($event['date_debut'] ?? '') ?></div>
            </div>
            <div class="mb-4">
                <div class="text-lg font-semibold">Fin :</div>
                <div><?= htmlspecialchars($event['date_fin'] ?? '') ?></div>
            </div>
            <div class="mb-6">
                <div class="text-lg font-semibold">Nombre max. de participants :</div>
                <div><?= htmlspecialchars($event['nb_max_participants'] ?? '') ?></div>
            </div>
            <?php if ($this->isInscrit): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">Vous êtes déjà inscrit à cet événement.</div>
            <?php else: ?>
                <form method="POST" action="/evenements/<?= urlencode($event['id_evenement']) ?>/inscrire" class="mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded">S'inscrire</button>
                </form>
            <?php endif; ?>
        </section>
        <?php
    }
}
