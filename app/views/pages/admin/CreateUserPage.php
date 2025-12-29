<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreateUserPage extends AuthTemplate
{
    private $roles;
    private $equipes;
    public function __construct($title = 'Créer un utilisateur', array $data = [])
    {
        parent::__construct($title);
        $this->roles = $data['roles'] ?? [];
        $this->equipes = $data['equipes'] ?? [];
    }

    protected function content()
    {
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer un utilisateur</h1>

            <?php
            $fields = [
                ['type' => 'text', 'name' => 'nom', 'label' => 'Nom', 'required' => true],
                ['type' => 'text', 'name' => 'prenom', 'label' => 'Prénom', 'required' => true],
                ['type' => 'email', 'name' => 'email', 'label' => 'Email', 'required' => true],
                ['type' => 'text', 'name' => 'username', 'label' => "Nom d'utilisateur", 'required' => true],
                ['type' => 'password', 'name' => 'password', 'label' => 'Mot de passe', 'required' => true],
                ['type' => 'text', 'name' => 'photo', 'label' => 'Photo (URL)'],
                [
                    'type' => 'select',
                    'name' => 'grade',
                    'label' => 'Grade',
                    'required' => true,
                    'options' => [
                        ['value' => 'Professeur', 'label' => 'Professeur'],
                        ['value' => 'Doctorant', 'label' => 'Doctorant'],
                        ['value' => 'Chercheur', 'label' => 'Chercheur'],
                        ['value' => 'Autre', 'label' => 'Autre'],
                    ]
                ],
                ['type' => 'textarea', 'name' => 'domaine_recherche', 'label' => 'Domaine de recherche'],
                ['type' => 'textarea', 'name' => 'biographie', 'label' => 'Biographie'],
                [
                    'type' => 'select',
                    'name' => 'role',
                    'label' => 'Rôle',
                    'required' => true,
                    'options' => array_map(fn($role) => [
                        'value' => $role['nom_role'],
                        'label' => $role['nom_role']
                    ], $this->roles)
                ],
                [
                    'type' => 'select',
                    'name' => 'id_equipe',
                    'label' => 'Équipe',
                    'options' => array_merge([
                        ['value' => '', 'label' => 'Aucune']
                    ], array_map(fn($equipe) => [
                            'value' => $equipe['id_equipe'],
                            'label' => $equipe['nom_equipe']
                        ], $this->equipes))
                ],
                [
                    'type' => 'select',
                    'name' => 'statut',
                    'label' => 'Statut',
                    'required' => true,
                    'options' => [
                        ['value' => 'actif', 'label' => 'Actif'],
                        ['value' => 'suspendu', 'label' => 'Suspendu'],
                        ['value' => 'inactif', 'label' => 'Inactif'],
                    ]
                ],
            ];
            $form = new FormBuilder($fields, '/admin/users', 'POST', 'Créer');
            $form->render();
            ?>
        </div>
        <?php
    }
}
