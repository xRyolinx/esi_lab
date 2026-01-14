<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class ReservationsPage extends AuthTemplate
{
    private array $reservations;
    public function __construct($title = 'Historique des réservations', array $data = [])
    {
        parent::__construct($title);
        $this->reservations = $data['reservations'] ?? [];
    }
    protected function content()
    {
        $reservations = $this->reservations;
        ?>
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Historique des réservations</h1>
                <a href="/admin/equipements" class="text-primary hover:underline">Retour aux équipements</a>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <?php if (count($reservations) === 0): ?>
                    <div class="text-gray-500">Aucune réservation trouvée.</div>
                <?php else: ?>
                    <table class="min-w-full bg-white rounded shadow">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Équipement</th>
                                <th class="px-4 py-2">Utilisateur</th>
                                <th class="px-4 py-2">Date début</th>
                                <th class="px-4 py-2">Date fin</th>
                                <th class="px-4 py-2">Date réservation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $r): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-center"><?= htmlspecialchars($r['id_reservation']) ?></td>
                                    <td class="px-4 py-2">
                                        <?php if (isset($r['equipement'])): ?>
                                            <a href="/admin/equipements/<?= urlencode($r['equipement']['id_equipement']) ?>" class="text-primary hover:underline">
                                                <?= htmlspecialchars($r['equipement']['nom']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-2">
                                        <?php if (isset($r['user'])): ?>
                                            <a href="/admin/users/<?= urlencode($r['user']['id_user']) ?>" class="text-primary hover:underline">
                                                <?= htmlspecialchars($r['user']['prenom'] . ' ' . $r['user']['nom']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($r['date_debut']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($r['date_fin']) ?></td>
                                    <td class="px-4 py-2 text-xs text-gray-500"><?= htmlspecialchars($r['date_reservation']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
