<?php
require_once __DIR__ . '/../../config/SessionManager.php';
require_once __DIR__ . '/../components/Notifications.php';
require_once __DIR__ . '/BaseTemplate.php';


abstract class AuthTemplate extends BaseTemplate
{
    public function __construct($title = "Espace Authentifié - ESI LAB")
    {
        parent::__construct($title);
    }

    // ------------- contenu à implémenter --------------
    abstract protected function content();


    // ------------- dans controller --------------
    public function render()
    {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <?php $this->head(); ?>
        <body class="bg-gray-50 flex min-h-screen">
            <?php $this->flashMessages(); ?>
            <?php $this->sidebar(); ?>
            <main class="flex-1 p-8">
                <?php $this->content(); ?>
            </main>
            <?php foreach ($this->jsFiles as $js): ?>
                <script src="<?php echo htmlspecialchars($js); ?>"></script>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
    }


    // ------------- template content --------------
    private function sidebar()
    {
        $links = [
            ['url' => '/admin/dashboard', 'label' => 'Dashboard', 'icon' => 'fa-tachometer-alt'],
            ['url' => '/admin/users', 'label' => 'Utilisateurs', 'icon' => 'fa-users-cog'],
            ['url' => '/admin/equipes', 'label' => 'Equipes', 'icon' => 'fa-users'],
            ['url' => '/admin/projets', 'label' => 'Projets', 'icon' => 'fa-project-diagram'],
            ['url' => '/admin/evenements', 'label' => 'Evenements', 'icon' => 'fa-calendar-alt'],
            ['url' => '/admin/equipement', 'label' => 'Equipement', 'icon' => 'fa-toolbox'],
            ['url' => '/admin/publications', 'label' => 'Publications', 'icon' => 'fa-book'],
            ['url' => '/admin/partenaires', 'label' => 'Partenaires', 'icon' => 'fa-handshake'],
        ];
        $current = $_SERVER['REQUEST_URI'];
        ?>
        <aside class="w-64 bg-primary text-white min-h-screen flex flex-col shadow-lg">
            <div class="flex items-center justify-center h-20 border-b border-primary-dark">
                <a href="/dashboard" class="flex items-center gap-2">
                    <img src="/img/logolcms.png" alt="Logo" class="h-12">
                    <span class="font-bold text-lg">ESI LAB</span>
                </a>
            </div>
            <nav class="flex-1 py-6">
                <ul class="space-y-2">
                    <?php foreach ($links as $link):
                        $active = strpos($current, $link['url']) === 0 ? 'active' : '';
                    ?>
                        <li>
                            <a href="<?= $link['url'] ?>" class="sidebar-link flex items-center gap-3 px-6 py-3 rounded transition hover:bg-secondary/80 <?= $active ?>">
                                <i class="fas <?= $link['icon'] ?> text-lg"></i>
                                <span><?= $link['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="p-6 border-t border-primary-dark">
                <a href="/logout" class="flex items-center gap-2 text-red-400 hover:text-red-600 transition">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>
        <?php
    }
}
