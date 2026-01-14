<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreatePublicationPage extends AuthTemplate
{
    public function __construct($title = 'Nouvelle publication', $data = [])
    {
        parent::__construct($title);
    }
    protected function content()
    {
        ?>
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
            <h1 class="text-2xl font-bold mb-6">Nouvelle publication</h1>
            <?php
            $fields = [
                [ 'type' => 'text', 'name' => 'titre', 'label' => 'Titre', 'required' => true ],
                [ 'type' => 'textarea', 'name' => 'resume', 'label' => 'Résumé'],
                [ 'type' => 'select', 'name' => 'type', 'label' => 'Type', 'required' => true,
                    'options' => [
                        ['value' => 'article', 'label' => 'Article'],
                        ['value' => 'these', 'label' => 'Thèse'],
                        ['value' => 'rapport', 'label' => 'Rapport'],
                        ['value' => 'communication', 'label' => 'Communication'],
                        ['value' => 'poster', 'label' => 'Poster'],
                    ]
                ],
                [ 'type' => 'text', 'name' => 'doi', 'label' => 'DOI' ],
                [ 'type' => 'text', 'name' => 'annee', 'label' => 'Année', 'required' => true ],
                [ 'type' => 'text', 'name' => 'domaine', 'label' => 'Domaine', 'required' => true ],
                [ 'type' => 'date', 'name' => 'date_publication', 'label' => 'Date de publication', 'required' => true ],
                [ 'type' => 'file', 'name' => 'fichier', 'label' => 'Fichier (PDF, DOCX)', 'required' => true ],
            ];
            $form = new FormBuilder($fields, '/admin/publications/new', 'POST', 'Créer', true);
            $form->render();
            ?>
            <div class="flex justify-end mt-4">
                <a href="/admin/publications" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Annuler</a>
            </div>
        </div>
        <?php
    }
}
