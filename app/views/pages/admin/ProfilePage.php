<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class ProfilePage extends AuthTemplate
{
    private $data;

    public function __construct($title = 'Mon profil', $data = [])
    {
        parent::__construct($title);
        $this->data = $data;
    }

    protected function content()
    {
        $user = $this->data['user'] ?? null;
        if (!$user) {
            echo '<h2>Profil introuvable.</h2>';
            return;
        }
        ?>
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 relative mt-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-6">
                    <img src="<?= htmlspecialchars($user['photo'] ?? '/public/img/default-user.png') ?>" alt="Photo de profil" class="rounded-full border-4 border-secondary shadow w-32 h-32 object-cover">
                    <div>
                        <h1 class="text-3xl font-bold mb-1"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
                        <div class="text-gray-500 text-lg mb-2">@<?= htmlspecialchars($user['username']) ?></div>
                        <span class="inline-block bg-secondary text-white text-xs px-3 py-1 rounded-full mr-2">Rôle : <?= htmlspecialchars($user['role']) ?></span>
                        <span class="inline-block bg-primary text-white text-xs px-3 py-1 rounded-full">Statut : <?= htmlspecialchars($user['statut']) ?></span>
                        <?php if (!empty($user['poste'])): ?>
                            <span class="inline-block bg-gray-200 text-primary text-xs px-3 py-1 rounded-full ml-2">Poste : <?= htmlspecialchars($user['poste']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="/admin/profile/edit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-secondary text-white rounded-lg shadow transition absolute right-8 top-8">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <p class="mb-2"><span class="font-semibold">Email :</span> <?= htmlspecialchars($user['email']) ?></p>
                    <p class="mb-2"><span class="font-semibold">Grade :</span> <?= htmlspecialchars($user['grade']) ?></p>
                    <p class="mb-2"><span class="font-semibold">Domaine de recherche :</span> <?= htmlspecialchars($user['domaine_recherche']) ?></p>
                </div>
                <div>
                    <p class="mb-2"><span class="font-semibold">Biographie :</span><br>
                        <span class="block bg-gray-100 rounded p-3 text-gray-700 min-h-[60px]"><?= nl2br(htmlspecialchars($user['biographie'])) ?></span>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}
