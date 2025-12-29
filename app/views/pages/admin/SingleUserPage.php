<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SingleUserPage extends AuthTemplate
{
    private $user;
    private $roles;
    private $equipes;
    public function __construct($title = 'Utilisateur', array $data = [])
    {
        parent::__construct($title);
        $this->user = $data['user'] ?? [];
        $this->roles = $data['roles'] ?? [];
        $this->equipes = $data['equipes'] ?? [];
    }

    protected function content()
    {
        $canEdit = SessionManager::hasPermissions(['users.write']);
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Utilisateur #<?= htmlspecialchars($this->user['id_user']) ?></h1>
            <?php
            // Flash messages
            foreach (['success', 'error'] as $type) {
                $msgs = SessionManager::getFlashMessage($type);
                if ($msgs) {
                    echo '<div class="mb-4 p-3 rounded ' . ($type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') . '">';
                    foreach ($msgs as $msg) {
                        echo '<div>' . htmlspecialchars($msg) . '</div>';
                    }
                    echo '</div>';
                }
            }
            $fields = [
                ['type' => 'text', 'name' => 'nom', 'label' => 'Nom', 'required' => true, 'value' => $this->user['nom'] ?? ''],
                ['type' => 'text', 'name' => 'prenom', 'label' => 'Prénom', 'required' => true, 'value' => $this->user['prenom'] ?? ''],
                ['type' => 'email', 'name' => 'email', 'label' => 'Email', 'required' => true, 'value' => $this->user['email'] ?? ''],
                ['type' => 'text', 'name' => 'username', 'label' => "Nom d'utilisateur", 'required' => true, 'value' => $this->user['username'] ?? ''],
                ['type' => 'text', 'name' => 'photo', 'label' => 'Photo (URL)', 'value' => $this->user['photo'] ?? ''],
                [
                    'type' => 'select',
                    'name' => 'grade',
                    'label' => 'Grade',
                    'required' => true,
                    'value' => $this->user['grade'] ?? '',
                    'options' => [
                        ['value' => 'Professeur', 'label' => 'Professeur'],
                        ['value' => 'Doctorant', 'label' => 'Doctorant'],
                        ['value' => 'Chercheur', 'label' => 'Chercheur'],
                        ['value' => 'Autre', 'label' => 'Autre'],
                    ]
                ],
                ['type' => 'textarea', 'name' => 'domaine_recherche', 'label' => 'Domaine de recherche', 'value' => $this->user['domaine_recherche'] ?? ''],
                ['type' => 'textarea', 'name' => 'biographie', 'label' => 'Biographie', 'value' => $this->user['biographie'] ?? ''],
                [
                    'type' => 'select',
                    'name' => 'role',
                    'label' => 'Rôle',
                    'required' => true,
                    'value' => $this->user['role'] ?? '',
                    'options' => array_map(fn($role) => [
                        'value' => $role['nom_role'],
                        'label' => $role['nom_role']
                    ], $this->roles)
                ],
                [
                    'type' => 'select',
                    'disabled' => true,
                    'name' => 'id_equipe',
                    'label' => 'Équipe',
                    'value' => $this->user['id_equipe'] ?? '',
                    'options' => array_merge([
                        ['value' => '', 'label' => 'Aucune']
                    ], array_map(fn($equipe) => [
                            'value' => $equipe['id_equipe'],
                            'label' => $equipe['nom_equipe']
                        ], $this->equipes)),
                ],
                [
                    'type' => 'select',
                    'name' => 'statut',
                    'label' => 'Statut',
                    'required' => true,
                    'value' => $this->user['statut'] ?? '',
                    'options' => [
                        ['value' => 'actif', 'label' => 'Actif'],
                        ['value' => 'suspendu', 'label' => 'Suspendu'],
                        ['value' => 'inactif', 'label' => 'Inactif'],
                    ]
                ],
            ];

            if ($canEdit) {
                $fields[] = ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'];
                $form = new FormBuilder($fields, "/admin/users/" . urlencode($this->user['id_user']), 'POST', 'Enregistrer', true);
                $form->render();

                // Delete button
                ?>
                <form method="POST" action="/admin/users/<?php echo urlencode($this->user['id_user']); ?>" class="mt-4"
                    onsubmit="return confirm('Supprimer cet utilisateur ?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit"
                        class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">Supprimer</button>
                </form>
                <?php
            } else {
                $form = new FormBuilder($fields, '', 'GET', '', disabled: true);
                $form->render();
            }
            ?>
        </div>
        <?php
    }
}
