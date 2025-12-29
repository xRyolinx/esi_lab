<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../models/Equipes.php';

class EquipesPage extends AuthTemplate
{
    private $equipes;
    public function __construct($title = 'Liste des équipes', array $data = [])
    {
        parent::__construct($title);
        $this->equipes = $data['equipes'] ?? [];
    }

    protected function content()
    {
        ?>
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Équipes du laboratoire</h1>
                <a href="/admin/equipes/new" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nouvelle équipe
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Date création</th>
                            <th class="px-4 py-2">Membres</th>
                            <th class="px-4 py-2"># Publications</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->equipes as $equipe): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-center text-secondary underline"><a href="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>"><?= htmlspecialchars($equipe['id_equipe']) ?></a></td>
                                <td class="px-4 py-2 font-semibold"><?= htmlspecialchars($equipe['nom_equipe']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($equipe['date_creation']) ?></td>
                                <td class="px-4 py-2"><?= isset($equipe['membres']) ? count($equipe['membres']) : '?' ?></td>
                                <td class="px-4 py-2 font-bold text-primary"><?= $equipe['nb_pubs'] ?? 0 ?></td>
                                <td class="px-4 py-2 text-center">
                                    <a href="/admin/equipes/<?= urlencode($equipe['id_equipe']) ?>" class="text-primary hover:underline">Détails</a>
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
