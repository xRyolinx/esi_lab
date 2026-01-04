<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreatePartenairePage extends AuthTemplate
{
    public function __construct($title = 'Créer un partenaire', array $data = [])
    {
        parent::__construct($title);
    }

    protected function content()
    {
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer un partenaire</h1>
            <?php
            $fields = [
                ['type' => 'text', 'name' => 'nom', 'label' => 'Nom', 'required' => true],
                ['type' => 'select', 'name' => 'type', 'label' => 'Type', 'required' => true,
                    'options' => [
                        ['label' => 'Universitaire', 'value' => 'universitaire'],
                        ['label' => 'Industriel', 'value' => 'industriel'],
                        ['label' => 'Organisme', 'value' => 'organisme'],
                    ]
                ],
                ['type' => 'text', 'name' => 'logo', 'label' => 'Logo (URL)'],
                ['type' => 'text', 'name' => 'site_web', 'label' => 'Site web'],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description'],
            ];
            $form = new FormBuilder($fields, '/admin/partenaires', 'POST', 'Créer');
            $form->render();
            ?>
        </div>
        <?php
    }
}
