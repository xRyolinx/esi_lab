<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class CreateEquipementPage extends AuthTemplate
{
    public function __construct($title = 'Ajouter un équipement')
    {
        parent::__construct($title);
    }
    protected function content()
    {
        ?>
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Ajouter un équipement</h1>
            <?php
            $fields = [
                [
                    'type' => 'text',
                    'name' => 'nom',
                    'label' => 'Nom',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'type',
                    'label' => 'Type',
                    'required' => true
                ],
                [
                    'type' => 'select',
                    'name' => 'statut',
                    'label' => 'Statut',
                    'options' => [
                        ['value' => 'disponible', 'label' => 'Disponible'],
                        ['value' => 'maintenance', 'label' => 'Maintenance']
                    ],
                    'value' => 'disponible',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'localisation',
                    'label' => 'Localisation',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                ]
            ];
            $form = new FormBuilder($fields, '/admin/equipements', 'POST', 'Créer');
            $form->render();
            ?>
        </div>
        <?php
    }
}
