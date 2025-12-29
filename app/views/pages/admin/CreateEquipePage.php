<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class CreateEquipePage extends AuthTemplate
{
    public function __construct($title = 'Créer une équipe', array $data = [])
    {
        parent::__construct($title);
    }

    protected function content()
    {
        require_once __DIR__ . '/../../components/FormBuilder.php';
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer une équipe</h1>
            <?php
            $fields = [
                [ 'type' => 'text', 'name' => 'nom_equipe', 'label' => "Nom de l'équipe", 'required' => true ],
                [ 'type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'required' => true ],
            ];
            $form = new FormBuilder($fields, '/admin/equipes', 'POST', 'Créer');
            $form->render();
            ?>
        </div>
        <?php
    }
}
