<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';
class SingleProjetPage extends AuthTemplate
{
    private $projet;
    public function __construct($title, array $data)
    {
        parent::__construct($title);
        $this->projet = $data['projet'];
    }
    public function content()
    {
        $projet = $this->projet;
        $canWrite = SessionManager::hasPermissions(['projets.write']);
        ?>
        <main class="px-5">
            <h1 class="text-3xl font-bold mb-8"><?= htmlspecialchars($this->projet['titre']) ?></h1>
            <?php
            $fields = [
                ['type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true, 'value' => $projet['titre']],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'value' => $projet['description']],
                ['type' => 'text', 'name' => 'thematique', 'label' => 'Thématique', 'value' => $projet['thematique']],
                ['type' => 'text', 'name' => 'type_financement', 'label' => 'Type de financement', 'value' => $projet['type_financement']],
                ['type' => 'date', 'name' => 'date_debut', 'label' => 'Date début', 'required' => true, 'value' => $projet['date_debut']],
                ['type' => 'date', 'name' => 'date_fin', 'label' => 'Date fin', 'value' => $projet['date_fin']],
                [
                    'type' => 'select',
                    'name' => 'statut',
                    'label' => 'Statut',
                    'value' => $projet['statut'],
                    'options' => [
                        ['value' => 'en_cours', 'label' => 'En cours'],
                        ['value' => 'termine', 'label' => 'Terminé'],
                        ['value' => 'soumis', 'label' => 'Soumis'],
                    ]
                ],
                ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            ];
            $form = new FormBuilder(
                $fields,
                '/admin/projets/' . urlencode($projet['id_projet']),
                'POST',
                'Enregistrer',
                disabled: !$canWrite
            );
            $form->render();
            ?>
        </main>
        <?php
    }
}
