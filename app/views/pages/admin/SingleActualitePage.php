<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SingleActualitePage extends AuthTemplate
{
    private $actualite;
    public function __construct($title = 'Actualité', array $data = [])
    {
        parent::__construct($title);
        $this->actualite = $data['actualite'] ?? [];
    }
    protected function content()
    {
        $a = $this->actualite;
        $canWrite = SessionManager::hasPermissions(['actualites.write']);
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Actualité : <?= htmlspecialchars($a['titre']) ?></h1>
                <?php if ($canWrite): ?>
                    <div class="flex gap-2">
                        <a href="/admin/actualites/<?= $a['id_actualite'] ?>/edit"
                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Modifier</a>
                        <form method="POST" action="/admin/actualites/<?= $a['id_actualite'] ?>/delete" style="display:inline">
                            <button type="submit" onclick="return confirm('Supprimer cette actualité ?')"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 ml-2">Supprimer</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-6 bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Description :</strong><br><?= nl2br(htmlspecialchars($a['description'])) ?></div>
                    <div><strong>Type :</strong> <?= htmlspecialchars($a['type']) ?></div>
                    <div><strong>Date publication :</strong> <?= htmlspecialchars($a['date_publication']) ?></div>
                </div>
            </div>

            <a href="/admin/actualites" class="block mt-4 text-blue-600 hover:underline">Retour à la liste</a>
        </div>
        <?php
    }
}
