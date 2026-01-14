<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class ParametresPage extends AuthTemplate
{
    private array $parametres;
    private ?string $success;
    private ?string $error;
    public function __construct($title = 'Paramètres', array $data = [])
    {
        parent::__construct($title);
        $this->parametres = $data['parametres'] ?? [];
        $this->success = $data['success'] ?? null;
        $this->error = $data['error'] ?? null;
    }
    protected function content()
    {
        $parametres = $this->parametres;
        ?>
        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Paramètres</h1>
            <?php if ($this->success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">Modifications enregistrées.</div>
            <?php elseif ($this->error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($this->error) ?></div>
            <?php endif; ?>
            <form method="POST" action="/admin/parametres">
                <table class="min-w-full bg-white rounded shadow mb-4">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-2">Clé</th>
                            <th class="px-4 py-2">Valeur</th>
                            <th class="px-4 py-2">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parametres as $p): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 font-mono text-xs text-gray-700"><?= htmlspecialchars($p['cle']) ?></td>
                                <td class="px-4 py-2">
                                    <input type="text" name="valeurs[<?= htmlspecialchars($p['cle']) ?>]" value="<?= htmlspecialchars($p['valeur']) ?>" class="border rounded px-2 py-1 w-full" />
                                </td>
                                <td class="px-4 py-2 text-xs text-gray-500"><?= htmlspecialchars($p['description'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Enregistrer</button>
            </form>
        </div>
        <?php
    }
}
