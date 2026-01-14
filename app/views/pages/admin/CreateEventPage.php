
<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreateEventPage extends AuthTemplate
{
    public function __construct($title = 'Créer un événement', $data = [])
    {
        parent::__construct($title);
    }

    protected function content()
    {
        $fields = [
            ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true],
            ['type' => 'textarea', 'name' => 'description', 'label' => 'Description'],
            ['type' => 'text', 'name' => 'type', 'label' => 'Type'],
            ['type' => 'checkbox', 'name' => 'isPublic', 'label' => 'Ouvert au Public', 'value' => '1'],
            ['type' => 'text', 'name' => 'lieu', 'label' => 'Lieu'],
            ['type' => 'datetime-local', 'name' => 'date_debut', 'label' => 'Date début'],
            ['type' => 'datetime-local', 'name' => 'date_fin', 'label' => 'Date fin'],
            ['type' => 'number', 'name' => 'nb_max_participants', 'label' => 'Nb max participants'],
        ];
        $form = new FormBuilder($fields, '/admin/evenements', 'POST', 'Créer');
        ?>
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer un événement</h1>
            <?php $form->render(); ?>
            <a href="/admin/evenements" class="text-blue-600 hover:underline">Retour à la liste</a>
        </div>
        <?php
    }
}
