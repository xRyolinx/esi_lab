<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreateActualitePage extends AuthTemplate
{
    public function __construct($title = 'Créer une actualité', $data = [])
    {
        parent::__construct($title);
    }

    protected function content()
    {
        $fields = [
            ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true],
            ['type' => 'textarea', 'name' => 'description', 'label' => 'Description'],
            ['type' => 'select', 'name' => 'type', 'label' => 'Type', 'required' => true, 'options' => [
                ['value' => 'projet', 'label' => 'Projet'],
                ['value' => 'publication', 'label' => 'Publication'],
                ['value' => 'evenement', 'label' => 'Événement'],
                ['value' => 'soutenance', 'label' => 'Soutenance'],
            ]],
        ];
        $form = new FormBuilder($fields, '/admin/actualites', 'POST', 'Créer');
        ?>
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer une actualité</h1>
            <?php $form->render(); ?>
            <a href="/admin/actualites" class="text-blue-600 hover:underline">Retour à la liste</a>
        </div>
        <?php
    }
}
