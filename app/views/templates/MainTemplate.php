<?php
require_once __DIR__ . '/../../config/SessionManager.php';
require_once __DIR__ . '/../components/Notifications.php';
require_once __DIR__ . '/BaseTemplate.php';
abstract class MainTemplate extends BaseTemplate
{
    public function __construct($title = "ESI LAB - Laboratoire de Recherche")
    {
        parent::__construct($title);

        // JS par défaut
        $this->addJS('/js/nav.js');

        // CSS par défaut
        $this->addCSS('/css/nav.css');
    }

    // ------------- contenu à implémenter --------------
    abstract protected function content();


    // ------------- dans controller --------------
    public function render()
    {
        ?>
        <!DOCTYPE html>
        <html lang="fr">

        <?php $this->head() ?>

        <body class="relative bg-gray-50">
            <?php $this->flashMessages() ?>

            <?php $this->header(); ?>

            <?php $this->content(); ?>

            <?php $this->footer(); ?>

            <!-- js  -->
            <?php foreach ($this->jsFiles as $js): ?>
                <script src="<?php echo htmlspecialchars($js); ?>"></script>
            <?php endforeach; ?>
        </body>

        </html>
        <?php
    }


    // ------------- template content --------------
    private function topbar()
    {
        $isLoggedIn = SessionManager::isLoggedIn();
        $userData = SessionManager::getUserData();
        $authLinks = [
            'dashboard' => ['/admin/dashboard', 'Dashboard', 'tachometer-alt'],
            'profil' => ['/profil', 'Profil', 'user-circle'],
            'logout' => ['/logout', 'Logout', 'sign-out-alt'],
        ];

        ?>
        <!-- Top bar (compacte) -->
        <div class="bg-primary text-white">
            <div class="container mx-auto px-4 flex justify-between items-center h-14">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <img src="/img/logolcms.png" alt="Logo" class="h-10">
                </a>

                <!-- Right actions -->
                <div class="flex items-center gap-6">
                    <a href="https://esi.dz" target="_blank"
                        class="hidden md:flex items-center text-sm border border-white px-3 py-1 rounded hover:bg-white hover:text-primary transition">
                        <i class="fas fa-university mr-2"></i>SITE ESI
                    </a>

                    <div class="flex gap-x-3">
                        <a href="#" class="text-white hover:text-secondary transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-secondary transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-secondary transition">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                    </div>

                    <?php if ($isLoggedIn): ?>
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-sm">
                                <i class="fas fa-user-circle text-lg"></i>
                                <span class="hidden md:inline"><?= htmlspecialchars($userData['prenom']) ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div
                                class="absolute right-0 py-2 px-4 w-44 z-[20]
                                bg-white rounded shadow-lg
                                opacity-0 invisible group-hover:opacity-100 group-hover:visible transition
                                flex flex-col gap-y-1
                                ">
                                <?php
                                $classLink = "text-primary text-sm block w-full px-3 py-2 rounded
                                dropdown-item hover:bg-gray-100
                                ";
                                ?>
                                <?php if ($userData['role'] === 'admin') {
                                    ?>
                                    <a href="/admin/dashboard" class="<?= $classLink ?>">Admin</a>
                                    <?php
                                }?>
                                <?php foreach ($authLinks as [$url, $label, $icon]): ?>
                                <a href="<?= $url ?>" class="<?= $classLink ?>">
                                    <i class="pr-2 fas fa-<?= $icon ?>"></i><?= $label ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="bg-secondary text-white text-sm px-4 py-1.5 rounded">
                            Connexion
                        </a>
                    <?php endif; ?>

                    <!-- Mobile menu button -->
                    <button id="menu-btn" class="md:hidden text-xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function nav()
    {
        $links = [
            'index' => ['/', 'Accueil', 'home'],
            'projets' => ['/projets', 'Projets', 'project-diagram'],
            'publications' => ['/publications', 'Publications', 'book'],
            'equipements' => ['/equipements', 'Équipements', 'laptop'],
            'membres' => ['/membres', 'Membres', 'users'],
            'evenements' => ['/evenements', 'Événements', 'calendar'],
            'contact' => ['/contact', 'Contact', 'envelope'],
        ];

        $currentPage = basename($_SERVER['PHP_SELF'], '.php');

        ?>
        <!-- Navigation -->
        <nav class="border-t">
            <div class="mx-auto px-4">
                <ul id="nav-menu" class="absolute w-full bg-white left-0 top-[-2000px] flex-col
                    md:relative md:top-0
                    md:flex md:items-center md:justify-center md:flex-row
                    gap-2 py-2 ">

                    <?php
                    foreach ($links as $key => [$url, $label, $icon]):
                        $active = $currentPage === $key
                            ? 'text-secondary bg-secondary/10'
                            : 'text-gray-700 hover:bg-gray-100';
                        ?>
                        <li>
                            <a href="<?= $url ?>" class="flex items-center gap-2 px-4 py-2 rounded text-sm <?= $active ?>">
                                <i class="fas fa-<?= $icon ?>"></i><?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </nav>
        <?php
    }

    private function header()
    {
        ?>
        <!-- Header -->
        <header class="sticky top-0 z-10 bg-white shadow-lg">
            <?php $this->topbar(); ?>
            <?php $this->nav(); ?>
        </header>
        <?php
    }

    private function footer()
    {
        ?>
        <!-- Footer -->
        <footer class="bg-primary text-white mt-16">
            <div class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Informations de contact -->
                    <div class="mx-auto">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-address-card mr-2"></i>Contact
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-3 text-secondary"></i>
                                <span>BP 68M, Oued Smar, 16270<br>Alger, Algérie</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone mr-3 text-secondary"></i>
                                <span>+213 (0) 21 80 00 00</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope mr-3 text-secondary"></i>
                                <a href="mailto:contact@laboratoire.esi.dz" class="hover:text-secondary transition">
                                    contact@laboratoire.esi.dz
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Liens rapides -->
                    <div class="mx-auto">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-link mr-2"></i>Liens Rapides
                        </h3>
                        <ul class="space-y-2">
                            <li><a href="/index.php" class="hover:text-secondary transition flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-xs"></i>Accueil</a></li>
                            <li><a href="/presentation.php" class="hover:text-secondary transition flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-xs"></i>Présentation</a></li>
                            <li><a href="/projets.php" class="hover:text-secondary transition flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-xs"></i>Projets</a></li>
                            <li><a href="/publications.php" class="hover:text-secondary transition flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-xs"></i>Publications</a></li>
                            <li><a href="/contact.php" class="hover:text-secondary transition flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-xs"></i>Contact</a></li>
                        </ul>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="mx-auto">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-share-alt mr-2"></i>Suivez-nous
                        </h3>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center hover:bg-secondary-light transition transform hover:scale-110">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center hover:bg-secondary-light transition transform hover:scale-110">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center hover:bg-secondary-light transition transform hover:scale-110">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center hover:bg-secondary-light transition transform hover:scale-110">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                        <div class="mt-6">
                            <a href="https://esi.dz" target="_blank" class="inline-block">
                                <img src="/img/logolcms.png" alt="Logo ESI"
                                    class="h-16 opacity-80 hover:opacity-100 transition">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm">
                    <p>&copy; <?php echo date('Y'); ?> Laboratoire de Recherche - École Supérieure d'Informatique. Tous droits
                        réservés.</p>
                </div>
            </div>
        </footer>

        <!-- Scroll to top button -->
        <button id="scroll-to-top" class="fixed bottom-8 right-8
        bg-primary hover:bg-black text-white w-12 h-12 rounded-full shadow-lg opacity-[0.5] transition-all duration-300 flex items-center justify-center z-50
        ">
            <i class="fas fa-arrow-up"></i>
        </button>
        <?php
    }
}
?>