<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreateProjetPage extends AuthTemplate
{
    public function content()
    {
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer un projet</h1>
            <?php
            $fields = [
                ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description'],
                ['type' => 'text', 'name' => 'thematique', 'label' => 'Thématique'],
                ['type' => 'text', 'name' => 'type_financement', 'label' => 'Type de financement'],
                ['type' => 'date', 'name' => 'date_debut', 'label' => 'Date début', 'required' => true],
                ['type' => 'date', 'name' => 'date_fin', 'label' => 'Date fin'],
            ];
            $form = new FormBuilder($fields, '/admin/projets', 'POST', 'Créer');
            $form->render();
            ?>
        </div>
        <?php
    }
}
