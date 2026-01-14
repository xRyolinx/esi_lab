<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class EditActualitePage extends AuthTemplate
{
    private $actualite;
    public function __construct($title = 'Modifier actualité', $data = [])
    {
        parent::__construct($title);
        $this->actualite = $data['actualite'] ?? [];
    }
    protected function content()
    {
        $a = $this->actualite;
        $fields = [
            ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true, 'value' => $a['titre'] ?? ''],
            ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'value' => $a['description'] ?? ''],
            ['type' => 'select', 'name' => 'type', 'label' => 'Type', 'required' => true, 'value' => $a['type'] ?? '', 'options' => [
                ['value' => 'projet', 'label' => 'Projet'],
                ['value' => 'publication', 'label' => 'Publication'],
                ['value' => 'evenement', 'label' => 'Événement'],
                ['value' => 'soutenance', 'label' => 'Soutenance'],
            ]],
        ];
        $form = new FormBuilder($fields, '/admin/actualites/' . ($a['id_actualite'] ?? '') . '/edit', 'POST', 'Enregistrer');
        ?>
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Modifier l'actualité</h1>
            <?php $form->render(); ?>
            <a href="/admin/actualites/<?= $a['id_actualite'] ?? '' ?>" class="text-blue-600 hover:underline">Retour</a>
        </div>
        <?php
    }
}
