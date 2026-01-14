<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class SingleUserPage extends MainTemplate
{
    private array $user;

    public function __construct($title = 'Profil membre', array $data = [])
    {
        parent::__construct($title);
        $this->user = $data['user'] ?? [];
    }

    protected function content()
    {
        $user = $this->user;
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Profil de
                <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
            <div class="mb-4 flex flex-col items-center">
                <img src="<?= htmlspecialchars($user['photo']) ?>" alt="photo membre"
                    class="w-20 h-20 rounded-full border-2 border-primary mb-2">
                <span class="font-bold text-lg text-primary">Poste : <?= htmlspecialchars($user['poste'] ?? '-') ?></span>
                <span class="text-sm text-gray-500">Grade : <?= htmlspecialchars($user['grade']) ?></span>
                <span class="text-sm text-gray-500">Email : <?= htmlspecialchars($user['email']) ?></span>
                <span class="text-sm text-gray-500">Rôle : <?= htmlspecialchars($user['role']) ?></span>
            </div>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Équipe</h2>
                <?php $equipe = $user['equipe'] ?? null; ?>
                <?php if ($equipe): ?>
                    <a href="/equipes/<?= urlencode($equipe['id_equipe']) ?>" class="text-primary underline font-bold">
                        <?= htmlspecialchars($equipe['nom_equipe']) ?>
                    </a>
                <?php else: ?>
                    <span class="text-gray-500">Aucune équipe</span>
                <?php endif; ?>
            </div>

            <!-- publications -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Publications</h2>
                <ul class="list-disc ml-8">
                    <?php foreach ($user['publications'] ?? [] as $pub): ?>
                        <li>
                            <a href=<?= "/publications/" . urlencode($pub['id_publication']) ?> class="text-primary underline">
                                <span class="font-bold text-primary"><?= htmlspecialchars($pub['titre']) ?></span>
                                <span class="text-sm text-gray-500">(<?= htmlspecialchars($pub['annee']) ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- projets -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Projets</h2>
                <ul class="list-disc ml-8">
                    <?php foreach ($user['projets'] ?? [] as $projet): ?>
                        <li>
                            <a href=<?= "/projets/" . urlencode($projet['id_projet']) ?> class="text-primary underline">
                                <?= htmlspecialchars($projet['titre']) ?> (<?= htmlspecialchars($projet['date_debut']) ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
    }
}
