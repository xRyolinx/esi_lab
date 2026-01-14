
<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class EditEventPage extends AuthTemplate
{
    private $event;
    public function __construct($title = 'Modifier événement', $data = [])
    {
        parent::__construct($title);
        $this->event = $data['event'] ?? [];
    }
    protected function content()
    {
        $event = $this->event;
        $fields = [
            ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true, 'value' => $event['titre'] ?? ''],
            ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'value' => $event['description'] ?? ''],
            ['type' => 'text', 'name' => 'type', 'label' => 'Type', 'value' => $event['type'] ?? ''],
            ['type' => 'checkbox', 'name' => 'isPublic', 'label' => 'Ouvert au Public', 'checked' => $event['isPublic']],
            ['type' => 'text', 'name' => 'lieu', 'label' => 'Lieu', 'value' => $event['lieu'] ?? ''],
            ['type' => 'datetime-local', 'name' => 'date_debut', 'label' => 'Date début', 'value' => str_replace(' ', 'T', $event['date_debut'])],
            ['type' => 'datetime-local', 'name' => 'date_fin', 'label' => 'Date fin', 'value' => str_replace(' ', 'T', $event['date_fin'])],
            ['type' => 'number', 'name' => 'nb_max_participants', 'label' => 'Nb max participants', 'value' => $event['nb_max_participants'] ?? ''],
        ];
        $form = new FormBuilder($fields, '/admin/evenements/' . ($event['id_evenement'] ?? '') . '/edit', 'POST', 'Enregistrer');
        ?>
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Modifier l'événement</h1>
            <?php $form->render(); ?>
            <a href="/admin/evenements/<?= $event['id_evenement'] ?? '' ?>" class="text-blue-600 hover:underline">Retour</a>
        </div>
        <?php
    }
}
