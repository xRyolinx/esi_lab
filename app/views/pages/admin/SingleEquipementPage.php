<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SingleEquipementPage extends AuthTemplate
{
    private $equipement;
    private $users;
    public function __construct($title, array $data)
    {
        parent::__construct($title);
        $this->equipement = $data['equipement'] ?? [];
        $this->users = $data['users'] ?? [];
    }
    protected function content()
    {
        $e = $this->equipement;
        $reservations = $e['reservations'] ?? [];
        $users = $this->users;
        $userId = SessionManager::getUserId();
        $canWrite = SessionManager::hasPermissions(['equipements.write']);
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Équipement : <?= htmlspecialchars($e['nom']) ?></h1>
                <?php if ($canWrite): ?>
                    <div class="flex gap-2">
                        <button onclick="document.getElementById('editEquipementForm').classList.toggle('hidden')"
                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Modifier</button>
                        <form method="POST" action="/admin/equipements/<?= urlencode($e['id_equipement']) ?>" onsubmit="return confirm('Supprimer cet équipement ? Toutes les réservations associées seront supprimées.');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-800 flex items-center gap-1" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-6 gap-4">
                <div class="mb-2"><strong>Type :</strong> <?= htmlspecialchars($e['type']) ?></div>
                <div class="mb-2"><strong>Localisation :</strong> <?= htmlspecialchars($e['localisation']) ?></div>
                <div class="mb-2"><strong>Description :</strong> <?= nl2br(htmlspecialchars($e['description'])) ?></div>
                <div class="mb-2 flex items-center gap-2">
                    <strong>Statut :</strong>
                    <form method="POST" action="/admin/equipements/<?= urlencode($e['id_equipement']) ?>/statut"
                        class="flex gap-2 items-center">
                        <select name="statut" id="statut" class="border px-2 py-1 rounded" <?= $canWrite ? '' : 'disabled' ?>>
                            <option value="disponible" <?= $e['statut'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="maintenance" <?= $e['statut'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                        <?php if ($canWrite): ?>
                            <button type="submit"
                                class="bg-primary text-white px-3 py-2 rounded hover:bg-primary-dark">Définir</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- form de modif -->
            <?php if ($canWrite): ?>
                <div id="editEquipementForm" class="hidden mb-8">
                    <h2 class="text-xl font-semibold mt-8 mb-2">Modifier les informations de l'équipement</h2>
                    <?php
                    $fields = [
                        [
                            'type' => 'hidden',
                            'name' => '_method',
                            'value' => 'PUT'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'nom',
                            'label' => 'Nom',
                            'required' => true,
                            'value' => $e['nom']
                        ],
                        [
                            'type' => 'text',
                            'name' => 'type',
                            'label' => 'Type',
                            'required' => true,
                            'value' => $e['type']
                        ],
                        [
                            'type' => 'text',
                            'name' => 'localisation',
                            'label' => 'Localisation',
                            'value' => $e['localisation']
                        ],
                        [
                            'type' => 'textarea',
                            'name' => 'description',
                            'label' => 'Description',
                            'value' => $e['description']
                        ]
                    ];
                    $form = new FormBuilder(
                        $fields,
                        "/admin/equipements/" . urlencode($e['id_equipement']),
                        'POST',
                        'Enregistrer'
                    );
                    $form->render();
                    ?>
                </div>
            <?php endif; ?>
            <hr class="my-8" />

            <!-- tableeau reservations -->
            <h2 class="text-xl font-semibold mb-4">Réservations</h2>
            <table class="min-w-full bg-white rounded shadow mb-6">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Utilisateur</th>
                        <th class="px-4 py-2">Début</th>
                        <th class="px-4 py-2">Fin</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) === 0): ?>
                        <tr>
                            <td colspan="<?= $canWrite ? 5 : 4 ?>" class="text-center text-gray-500">Aucune réservation.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($reservations as $r):
                        $user = $r['user'];
                        $rowId = 'res-row-' . $r['id_reservation'];
                        ?>
                        <tr class="border-b hover:bg-gray-50" id="<?= $rowId ?>">
                            <td class="px-4 py-2 text-center">#<?= htmlspecialchars($r['id_reservation']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></td>
                            <td class="px-4 py-2 res-date-debut"><span><?= htmlspecialchars($r['date_debut']) ?></span></td>
                            <td class="px-4 py-2 res-date-fin"><span><?= htmlspecialchars($r['date_fin']) ?></span></td>
                            <td class="px-4 py-2 text-center res-actions">
                                <?php if ($canWrite || $userId == $r['id_user']): ?>
                                    <div class="normal-mode">
                                        <button type="button" class="text-blue-500 hover:text-blue-700 transition-colors edit-res-btn"
                                            data-res-id="<?= $r['id_reservation'] ?>" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 align-middle" fill="none"
                                                viewBox="0 0 24 24" stroke="#3B82F6">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 5.487a2.25 2.25 0 113.182 3.182L8.75 19.963l-4.182.5.5-4.182 10.294-10.294z" />
                                            </svg>
                                        </button>
                                        <form method="POST" action="/admin/reservations/<?= urlencode($r['id_reservation']) ?>"
                                            style="display:inline" onsubmit="return confirm('Supprimer cette réservation ?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" title="Supprimer"
                                                class="text-red-600 hover:text-red-800 bg-transparent border-0 p-0 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div style="display: none;"
                                    class="flex gap-2 items-center edit-mode">
                                        <form method="POST" action=<?="/admin/reservations/" . $r['id_reservation'] ?>
                                            class="w-full flex gap-2 res-edit-form">
                                            <input type="hidden" name="_method" value="PUT" />
                                            <input type="datetime-local" name="date_debut" value=<?= str_replace(' ', 'T', $r['date_debut']) ?>
                                                class="border px-2 py-1 rounded w-full" required />
                                            <input type="datetime-local" name="date_fin" value=<?= str_replace(' ', 'T', $r['date_fin']) ?>
                                                class="border px-2 py-1 rounded w-full" required />
                                            <button type="submit" title="Enregistrer"
                                                class="text-green-600 hover:text-green-800 bg-transparent border-0 p-0 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button type="button" title="Annuler"
                                            class="text-gray-400 hover:text-gray-600 bg-transparent border-0 p-0 m-0 res-cancel-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-linewidth="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php $this->scriptEditReservation(); ?>

            <!-- new reservation -->
            <h3 class="text-lg font-semibold mb-2">Nouvelle réservation</h3>
            <form method="POST" action="/admin/equipements/<?= urlencode($e['id_equipement']) ?>/reserver"
                class="bg-gray-50 p-4 rounded shadow flex flex-col gap-2 max-w-lg">
                <div class="flex gap-2 items-center">
                    <div class="flex gap-2 items-center">
                        <label for="date_debut" class="mb-0">Début</label>
                        <input type="datetime-local" name="date_debut" id="date_debut" class="border px-2 py-1 rounded"
                            required />
                    </div>
                    <div class="flex gap-2 items-center">
                        <label for="date_fin" class="mb-0">Fin</label>
                        <input type="datetime-local" name="date_fin" id="date_fin" class="border px-2 py-1 rounded" required />
                    </div>
                </div>
                <?php if ($canWrite): ?>
                    <div class="flex gap-2 items-center">
                        <label for="user_id">Utilisateur</label>
                        <select name="user_id" id="user_id" class="border px-2 py-1 rounded">
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id_user'] ?>"><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <button type="submit"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark mt-2">Réserver</button>
            </form>
        </div>
        <?php
    }

    private function scriptEditReservation()
    {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.edit-res-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const resId = btn.getAttribute('data-res-id');
                        const row = document.getElementById('res-row-' + resId);
                        if (!row) return;
                        // Get current values
                        const tdDebut = row.querySelector('.res-date-debut');
                        const tdFin = row.querySelector('.res-date-fin');
                        const actionsTd = row.querySelector('.res-actions');
                        
                        // Hide the td inputs and show new buttons
                        tdDebut.style.display = 'none';
                        tdFin.style.display = 'none';
                        actionsTd.colSpan = 3;
                        actionsTd.querySelector('.normal-mode').style.display = 'none';
                        actionsTd.querySelector('.edit-mode').style.display = 'flex';
                        
                        // Cancel button handler
                        actionsTd.querySelector('.res-cancel-btn').addEventListener('click', function () {
                            tdDebut.removeAttribute('style');
                            tdFin.removeAttribute('style');
                            actionsTd.colSpan = 1;
                            actionsTd.querySelector('.edit-mode').style.display = 'none';
                            actionsTd.querySelector('.normal-mode').style.display = 'block';
                        });
                    });
                });
            });
        </script>
        <?php
    }
}
