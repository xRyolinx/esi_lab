<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class DashboardPage extends AuthTemplate
{
    public function __construct($title = "Dashboard - ESI LAB")
    {
        parent::__construct($title);
    }

    protected function content()
    {
        $links = [
            'Utilisateurs' => ['/admin/users', 'fa-users-cog'],
            'Equipes' => ['/admin/equipes', 'fa-users'],
            'Projets' => ['/admin/projets', 'fa-project-diagram'],
            'Evenements' => ['/admin/evenements', 'fa-calendar-alt'],
            'Equipement' => ['/admin/equipement', 'fa-toolbox'],
            'Publications' => ['/admin/publications', 'fa-book'],
            'Partenaires' => ['/admin/partenaires', 'fa-handshake'],
        ];
        ?>
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Bienvenue sur le Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($links as $name => [$url, $icon]) : ?>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <i class="fas <?= $icon ?> text-3xl text-secondary mb-2"></i>
                    <div class="text-lg font-semibold"><?= $name ?></div>
                    <a href="<?= $url ?>" class="mt-2 text-secondary hover:underline">Gérer</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
