<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class AdminContactsPage extends AuthTemplate
{
    private array $contacts;
    public function __construct($title = 'Contacts', array $data = [])
    {
        parent::__construct($title);
        $this->contacts = $data['contacts'] ?? [];
    }
    protected function content()
    {
        $contacts = $this->contacts;
        ?>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Messages de contact reçus</h1>
            <div class="bg-white rounded-xl shadow p-6">
                <?php if (count($contacts) === 0): ?>
                    <div class="text-gray-500">Aucun message reçu.</div>
                <?php else: ?>
                    <table class="min-w-full bg-white rounded shadow mb-4">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="px-4 py-2">Nom</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Sujet</th>
                                <th class="px-4 py-2">Message</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $c): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['nom']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['email']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['sujet']) ?></td>
                                    <td class="px-4 py-2 max-w-xs truncate" title="<?= htmlspecialchars($c['message']) ?>">
                                        <?= nl2br(htmlspecialchars($c['message'])) ?>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-500"><?= htmlspecialchars($c['date_envoi']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
