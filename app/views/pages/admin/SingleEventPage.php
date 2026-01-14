<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SingleEventPage extends AuthTemplate
{
    private $event;
    private $inscrits;
    public function __construct($title = 'Événement', array $data = [])
    {
        parent::__construct($title);
        $this->event = $data['event'] ?? [];
        $this->inscrits = $data['inscrits'] ?? [];
    }
    protected function content()
    {
        $event = $this->event;
        $inscrits = $this->inscrits;
        $canWrite = SessionManager::hasPermissions(['events.write']);
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Événement : <?= htmlspecialchars($event['titre']) ?></h1>
                <?php if ($canWrite): ?>
                    <div class="flex gap-2">
                        <a href="/admin/evenements/<?= $event['id_evenement'] ?>/edit"
                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">Modifier</a>
                        <form method="POST" action="/admin/evenements/<?= $event['id_evenement'] ?>/delete" style="display:inline">
                            <button type="submit" onclick="return confirm('Supprimer cet événement ?')"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 ml-2">Supprimer</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-6 bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Description :</strong><br><?= nl2br(htmlspecialchars($event['description'])) ?></div>
                    <div><strong>Type :</strong> <?= htmlspecialchars($event['type']) ?></div>
                    <div><strong>Public :</strong> <?= !empty($event['isPublic']) ? 'Oui' : 'Non' ?></div>
                    <div><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></div>
                    <div><strong>Date début :</strong> <?= htmlspecialchars($event['date_debut']) ?></div>
                    <div><strong>Date fin :</strong> <?= htmlspecialchars($event['date_fin']) ?></div>
                    <div><strong>Nb max participants :</strong> <?= htmlspecialchars($event['nb_max_participants'] ?? '/') ?>
                    </div>
                </div>
            </div>

            <!-- non inscrits -->
             <!-- non users inscrits -->
            <?php
            $nbAnon = 0;
            foreach ($inscrits as $user) {
                if (!isset($user['nom']) || $user['nom'] === null) {
                    $nbAnon++;
                }
            }
            ?>
            <div class="mb-6">
                <span class="font-semibold">Nombre d'inscrits non authentifiés :</span>
                <span><?= $nbAnon ?></span>
            </div>

            <!-- users inscrits -->
            <div class="mb-6 bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Inscrits</h2>
                <?php if (count($inscrits) === 0): ?>
                    <div class="text-gray-500">Aucun inscrit pour cet événement.</div>
                <?php else: ?>
                    <table class="min-w-full bg-white rounded shadow mb-4">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="px-4 py-2">Nom</th>
                                <th class="px-4 py-2">Prénom</th>
                                <th class="px-4 py-2">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscrits as $user): ?>
                                <?php if (isset($user['nom'])): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2"><?= htmlspecialchars($user['nom'] ?? $user->nom) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($user['prenom'] ?? $user->prenom) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($user['email'] ?? $user->email) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <a href="/admin/evenements" class="block mt-4 text-blue-600 hover:underline">Retour à la liste</a>
        </div>
        <?php
    }
}
