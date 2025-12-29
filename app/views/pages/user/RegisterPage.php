<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class RegisterPage extends MainTemplate
{
    private $roles;

    public function __construct($title = "Inscription - ESI LAB", array $data = [])
    {
        parent::__construct($title);
        $this->roles = $data['roles'] ?? [];
    }

    protected function content()
    {
        $this->main();
    }

    private function main()
    {
        $fields = [
            ['type' => 'text', 'name' => 'nom', 'label' => 'Nom', 'required' => true],
            ['type' => 'text', 'name' => 'prenom', 'label' => 'Prénom', 'required' => true],
            ['type' => 'email', 'name' => 'email', 'label' => 'Email', 'required' => true],
            [
                'type' => 'text',
                'name' => 'username',
                'label' =>
                    "Nom d'utilisateur",
                'required' => true
            ],
            ['type' => 'password', 'name' => 'password', 'label' => 'Mot de passe', 'required' => true],
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
        ];
        $form = new FormBuilder($fields, '/register', 'POST', "S'inscrire");
        
        ?>
        <main class="container mx-auto px-4 py-16 flex justify-center items-center min-h-[60vh]">
            <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
                <h1 class="text-2xl font-semibold text-center mb-6">Inscription</h1>
                <?= $form->render() ?>
                <p class="mt-6 text-center text-sm text-gray-600">
                    Déjà inscrit ? <a href="/login" class="text-secondary hover:underline">Se connecter</a>
                </p>
            </div>
        </main>
        <?php
    }
}
