<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/Organigramme.php';

class EquipesPage extends MainTemplate
{
    private array $labInfo;
    private array $equipes;
    private array $users;
    private array $directeur;
    private array $postes;

    public function __construct($title = 'Les équipes de recherche', array $data = [])
    {
        parent::__construct($title);
        $this->labInfo = $data['labInfo'] ?? [];
        $this->equipes = $data['equipes'] ?? [];
        $this->users = $data['users'] ?? [];
        $this->directeur = $data['directeur'] ?? [];
        $this->postes = $data['postes'] ?? [];
    }

    protected function content()
    {
        ?>
        <section class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold text-primary mb-6">Présentation du laboratoire</h1>
            <div class="mb-8 text-lg text-gray-700">
                <?= nl2br(htmlspecialchars($this->labInfo['description'] ?? '')) ?>
            </div>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-secondary mb-4">Thèmes de recherche</h2>
                <ul class="list-disc ml-8 text-gray-700">
                    <?php foreach ($this->labInfo['themes'] ?? [] as $theme): ?>
                        <li><?= htmlspecialchars($theme) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-secondary mb-4">Organigramme du laboratoire</h2>
                <?php if (!empty($this->directeur)): ?>
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-2">Directeur</h3>
                        <div class="flex flex-wrap gap-8 justify-start">
                            <div class="flex flex-col items-center mb-4">
                                <img src="<?= htmlspecialchars($this->directeur['photo']) ?>" alt="photo directeur"
                                    class="w-20 h-20 rounded-full border-2 border-primary mb-2">
                                <span
                                    class="font-bold text-lg"><?= htmlspecialchars($this->directeur['prenom'] . ' ' . $this->directeur['nom']) ?></span>
                                <span class="text-sm text-gray-500">Directeur</span>
                                <span class="text-sm text-gray-500">Grade :
                                    <?= htmlspecialchars($this->directeur['grade']) ?></span>
                                <a href="/users/<?= urlencode($this->directeur['id_user']) ?>"
                                    class="text-secondary underline">Biographie & publications</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-2">Membres occupant des postes</h3>
                    <div class="flex flex-wrap gap-8 justify-start">
                        <?php foreach ($this->postes as $user): ?>
                            <?php if (!empty($user['poste']) && $user['poste'] != '' && $user['poste'] != 'Directeur'): ?>
                                <div class="flex flex-col items-center">
                                    <img src="<?= htmlspecialchars($user['photo']) ?>" alt="photo membre"
                                        class="w-16 h-16 rounded-full border border-gray-300 mb-2">
                                    <span
                                        class="font-bold text-base text-primary"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                                    <span class="text-sm text-gray-500">Poste : <?= htmlspecialchars($user['poste'] ?? '-') ?></span>
                                    <span class="text-sm text-gray-500">Grade : <?= htmlspecialchars($user['grade']) ?></span>
                                    <a href="/users/<?= urlencode($user['id_user']) ?>" class="text-secondary underline">Biographie &
                                        publications</a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- equipes -->
            <div>
                <h2 class="text-2xl font-bold text-secondary mb-4">Présentation des équipes</h2>
                <?php foreach ($this->equipes as $equipe): ?>
                    <div class="mb-12 p-6 bg-white rounded shadow
                    flex flex-col gap-6">
                        <!-- nom equipe -->
                        <h3 class="text-xl font-bold text-primary mb-2">
                            <a href="/equipes/<?= urlencode($equipe['id_equipe']) ?>" class="underline text-primary">
                                <?= htmlspecialchars($equipe['nom_equipe']) ?>
                            </a>
                        </h3>

                        <!-- chef -->
                        <?php $chef = $equipe['chef'] ?? null; ?>
                        <?php if (!empty($chef)): ?>
                            <div class="mb-4">
                                <span class="font-semibold">Chef d'équipe :</span>
                                <div class="flex flex-wrap gap-6 mt-2">
                                    <div class="flex flex-col items-center">
                                        <img src="<?= htmlspecialchars($chef['photo']) ?>" alt="photo membre"
                                            class="w-12 h-12 rounded-full border border-gray-300 mb-1">
                                        <span class="text-base font-bold text-primary">
                                            <?= htmlspecialchars($chef['prenom'] . ' ' . $chef['nom']) ?>
                                        </span>
                                        <span class="text-sm text-gray-500">Poste :
                                            <?= htmlspecialchars($chef['poste'] ?? 'Chef d\'equipe') ?>
                                        </span>
                                        <span class="text-sm text-gray-500">Grade :
                                            <?= htmlspecialchars($chef['grade']) ?>
                                        </span>
                                        <a href="/users/<?= urlencode($chef['id_user']) ?>" class="text-secondary underline">Biographie
                                            &
                                            publications</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- membres -->
                        <div class="mb-4">
                            <span class="font-semibold">Membres :</span>
                            <div class="flex flex-wrap gap-6 mt-2">
                                <?php foreach ($equipe['membres'] ?? [] as $user): ?>
                                    <?php if (!empty($chef) && $chef['id_user'] != $user['id_user']): ?>
                                        <div class="flex flex-col items-center">
                                            <img src="<?= htmlspecialchars($user['photo']) ?>" alt="photo membre"
                                                class="w-12 h-12 rounded-full border border-gray-300 mb-1">
                                            <span
                                                class="text-base font-bold text-primary"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                                            <span class="text-sm text-gray-500">Poste :
                                                <?= htmlspecialchars($user['poste'] ?? '-') ?></span>
                                            <span class="text-sm text-gray-500">Grade : <?= htmlspecialchars($user['grade']) ?></span>
                                            <a href="/users/<?= urlencode($user['id_user']) ?>" class="text-secondary underline">Biographie
                                                & publications</a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
