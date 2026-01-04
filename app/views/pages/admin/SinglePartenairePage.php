<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';
require_once __DIR__ . '/../../../config/SessionManager.php';

class SinglePartenairePage extends AuthTemplate
{
    private $partenaire;
    public function __construct($title = 'Partenaire', array $data = [])
    {
        parent::__construct($title);
        $this->partenaire = $data['partenaire'] ?? [];
    }

    protected function content()
    {
        $canEdit = SessionManager::hasPermissions(['partenaires.write']);
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Partenaire #<?= htmlspecialchars($this->partenaire['id_partenaire']) ?></h1>
            <?php
            foreach (['success', 'error'] as $type) {
                $msgs = SessionManager::getFlashMessage($type);
                if ($msgs) {
                    echo '<div class="mb-4 p-3 rounded ' . ($type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') . '">';
                    foreach ($msgs as $msg) {
                        echo '<div>' . htmlspecialchars($msg) . '</div>';
                    }
                    echo '</div>';
                }
            }
            $fields = [
                ['type' => 'text', 'name' => 'nom', 'label' => 'Nom', 'required' => true, 'value' => $this->partenaire['nom'] ?? ''],
                ['type' => 'text', 'name' => 'type', 'label' => 'Type', 'required' => true, 'value' => $this->partenaire['type'] ?? ''],
                ['type' => 'text', 'name' => 'logo', 'label' => 'Logo (URL)', 'value' => $this->partenaire['logo'] ?? ''],
                ['type' => 'text', 'name' => 'site_web', 'label' => 'Site web', 'value' => $this->partenaire['site_web'] ?? ''],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'value' => $this->partenaire['description'] ?? ''],
            ];
            if ($canEdit) {
                $fields[] = ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'];
                $form = new FormBuilder($fields, "/admin/partenaires/" . urlencode($this->partenaire['id_partenaire']), 'POST', 'Enregistrer', true);
                $form->render();
                ?>
                <form method="POST" action="/admin/partenaires/<?php echo urlencode($this->partenaire['id_partenaire']); ?>" class="mt-4" onsubmit="return confirm('Supprimer ce partenaire ?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">Supprimer</button>
                </form>
                <?php
            } else {
                $form = new FormBuilder($fields, '', 'GET', '', disabled: true);
                $form->render();
            }
            ?>
        </div>
        <?php
    }
}
