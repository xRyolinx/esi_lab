<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../models/Partenaires.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class PartenairesPage extends AuthTemplate
{
    private $partenaires;

    public function __construct($title = 'Gestion des partenaires', array $data = [])
    {
        parent::__construct($title);
        $this->partenaires = $data['partenaires'] ?? [];
    }

    protected function content()
    {
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Partenaires</h1>
                <?php if (SessionManager::hasPermissions(['partenaires.write'])): ?>
                    <a href="/admin/partenaires/new"
                        class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Créer partenaire
                    </a>
                <?php endif; ?>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Logo</th>
                            <th class="px-4 py-2">Site web</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->partenaires as $p): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-center underline text-secondary"><a
                                        href="/admin/partenaires/<?= urlencode($p['id_partenaire']) ?>"><?= htmlspecialchars($p['id_partenaire']) ?></a>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($p['nom']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($p['type']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if (!empty($p['logo'])): ?>
                                        <img src="<?= htmlspecialchars($p['logo']) ?>" alt="logo" class="h-8 w-8 object-contain" />
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if (!empty($p['site_web'])): ?>
                                        <a href="<?= htmlspecialchars($p['site_web']) ?>" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            <?= htmlspecialchars($p['site_web']) ?>
                                        </a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="/admin/partenaires/<?= urlencode($p['id_partenaire']) ?>"
                                        class="text-primary hover:underline">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
