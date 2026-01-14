<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class EditPublicationPage extends AuthTemplate
{
    private $publication;
    private $users;
    private $projets;
    private $user_projets_ids;

    public function __construct($title = 'Modifier la publication', $data = [])
    {
        parent::__construct($title);
        $this->publication = $data['publication'] ?? null;
        $this->users = $data['users'] ?? [];
        $this->projets = $data['projets'] ?? [];
        $this->user_projets_ids = $data['user_projets_ids'] ?? [];
    }
    protected function content()
    {
        $pub = $this->publication;
        $users = $this->users;
        $projets = $this->projets;
        $user_projets_ids = $this->user_projets_ids;

        if (!$pub) {
            echo '<h2>Publication introuvable.</h2>';
            return;
        }
        ?>
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Modifier la publication</h1>
                <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>" method="post" onsubmit="return confirm('Confirmer la suppression de cette publication ?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-700 text-white rounded flex items-center gap-2" title="Supprimer">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
            <?php
            $fields = [
                [ 'type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'value' => $pub['titre'] ?? '', 'required' => true ],
                [ 'type' => 'textarea', 'name' => 'resume', 'label' => 'Résumé', 'value' => $pub['resume'] ?? '', 'required' => true ],
                [ 'type' => 'text', 'name' => 'type', 'label' => 'Type', 'value' => $pub['type'] ?? '', 'required' => true ],
                [ 'type' => 'text', 'name' => 'doi', 'label' => 'DOI', 'value' => $pub['doi'] ?? '' ],
                [ 'type' => 'text', 'name' => 'annee', 'label' => 'Année', 'value' => $pub['annee'] ?? '', 'required' => true ],
                [ 'type' => 'text', 'name' => 'domaine', 'label' => 'Domaine', 'value' => $pub['domaine'] ?? '', 'required' => true ],
                [ 'type' => 'date', 'name' => 'date_publication', 'label' => 'Date de publication', 'value' => $pub['date_publication'] ?? '', 'required' => true ],
                [ 'type' => 'file', 'name' => 'fichier', 'label' => 'Nouveau fichier (PDF, DOCX)' ],
            ];
            $form = new FormBuilder($fields, '/admin/publications/' . $pub['id_publication'] . '/edit', 'POST', 'Enregistrer', true);
            $form->render();
            ?>
            <div class="flex justify-end mt-4">
                <a href="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Annuler</a>
            </div>
        </div>

        <!-- Section auteurs -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
            <h2 class="text-xl font-bold mb-4">Auteurs</h2>
            <ul class="mb-4">
                <?php
                $user = $_SESSION['user'] ?? [];
                $userId = $user['id_user'] ?? null;
                $canWrite = SessionManager::hasPermissions(['publications.write']);
                ?>
                <?php if (!empty($pub['auteurs'])): ?>
                    <?php foreach ($pub['auteurs'] as $auteur): ?>
                        <li class="mb-1 flex items-center gap-2">
                            👤 <?= htmlspecialchars($auteur['prenom'] . ' ' . $auteur['nom']) ?> (<?= htmlspecialchars($auteur['email']) ?>)
                            <?php if ($canWrite || $auteur['id_user'] == $userId): ?>
                                <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/remove-auteur" method="post" style="display:inline">
                                    <input type="hidden" name="id_user" value="<?= htmlspecialchars($auteur['id_user']) ?>">
                                    <button type="submit" class="ml-2 text-red-500 hover:text-red-700" title="Retirer" onclick="return confirm('Confirmer la suppression de cet auteur ?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-500">Aucun auteur</li>
                <?php endif; ?>
            </ul>
            <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/add-auteur" method="post" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label for="id_user" class="block text-sm font-medium mb-1">Ajouter un auteur</label>
                    <select name="id_user" id="id_user" class="input input-bordered w-full">
                        <option value="">Sélectionner un utilisateur</option>
                        <?php
                        $allUsers = $users;
                        $currentAuteurIds = array_column($pub['auteurs'] ?? [], 'id_user');
                        foreach ($allUsers as $user) {
                            if (!in_array($user['id_user'], $currentAuteurIds)) {
                                echo '<option value="' . htmlspecialchars($user['id_user']) . '">' . htmlspecialchars($user['prenom'] . ' ' . $user['nom'] . ' (' . $user['email'] . ')') . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded">Ajouter</button>
            </form>
        </div>

        <!-- Section projets -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
            <h2 class="text-xl font-bold mb-4">Projets liés</h2>
            <ul class="mb-4">
                <?php
                $userProjetsIds = $user_projets_ids ?? [];
                ?>
                <?php if (!empty($pub['projets'])): ?>
                    <?php foreach ($pub['projets'] as $projet): ?>
                        <li class="mb-1 flex items-center gap-2">
                            📁 <?= htmlspecialchars($projet['titre']) ?>
                            <?php if ($canWrite || in_array($projet['id_projet'], $userProjetsIds)): ?>
                                <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/remove-projet" method="post" style="display:inline">
                                    <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']) ?>">
                                    <button type="submit" class="ml-2 text-red-500 hover:text-red-700" title="Retirer" onclick="return confirm('Confirmer la suppression de ce projet ?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-500">Aucun projet lié</li>
                <?php endif; ?>
            </ul>
            <form action="/admin/publications/<?= htmlspecialchars($pub['id_publication']) ?>/add-projet" method="post" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label for="id_projet" class="block text-sm font-medium mb-1">Affecter à un projet</label>
                    <select name="id_projet" id="id_projet" class="input input-bordered w-full">
                        <option value="">Sélectionner un projet</option>
                        <?php
                        $allProjets = $projets;
                        $currentProjetIds = array_column($pub['projets'] ?? [], 'id_projet');
                        foreach ($allProjets as $projet) {
                            if (!in_array($projet['id_projet'], $currentProjetIds)) {
                                echo '<option value="' . htmlspecialchars($projet['id_projet']) . '">' . htmlspecialchars($projet['titre']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded">Affecter</button>
            </form>
        </div>
        <?php
    }
}
