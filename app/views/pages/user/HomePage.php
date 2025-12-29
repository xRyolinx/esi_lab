<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/Diaporama.php';
class HomePage extends MainTemplate
{
    public function __construct($title = "Accueil - ESI LAB")
    {
        parent::__construct($title);

        $this->addJS('/js/diaporama.js');
    }


    // contenu
    protected function content()
    {
        ?>
        <?php $this->diaporama(); ?>

        <?php $this->main(); ?>

        <?php
    }


    // composants
    private function diaporama()
    {
        $diapo = new Diaporama([
            [
                'text' => 'Découvrez nos projets innovants',
                'link' => '/projets',
                'img' => '/img/logolcms.png'
            ],
            [
                'text' => 'Découvrez nos derniers événements',
                'link' => '/evenements',
                'img' => '/img/logolcms.png'
            ],
            [
                'text' => 'Découvrez notre équipe de recherche',
                'link' => '/equipes',
                'img' => '/img/logolcms.png'
            ]
        ]);
        $diapo->render();
    }

    private function actualite()
    {
        ?>
        <!-- Section 1 : Actualités scientifiques -->
        <section id="actualites" class="container mx-auto px-4 py-12">
            <h2 class="text-3xl font-bold text-primary mb-6">Actualités scientifiques</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Exemple de carte d’actualité -->
                <!-- À remplacer par un loop PHP avec les données réelles -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">Nouveau projet IA</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <a href="#" class="text-secondary font-semibold hover:underline">Voir le détail →</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">Publication récente</h3>
                    <p class="text-gray-600 mb-4">Résumé ou bref descriptif de la publication.</p>
                    <a href="#" class="text-secondary font-semibold hover:underline">Voir le détail →</a>
                </div>
            </div>
        </section>
        <?php
    }

    private function presentation_lab()
    {
        ?>
        <!-- Section 2 : Présentation du laboratoire et organigramme -->
        <section id="presentation" class="bg-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-primary mb-6">À propos du laboratoire</h2>
                <p class="text-gray-700 max-w-3xl mx-auto mb-8">
                    Brève présentation du laboratoire, ses missions, domaines de recherche et organigramme.
                </p>
                <!-- Placeholder organigramme -->
                <div class="flex justify-center">
                    <img src="/img/organigramme-placeholder.png" alt="Organigramme" class="rounded shadow-lg w-full max-w-4xl">
                </div>
            </div>
        </section>
        <?php
    }

    private function evenements()
    {
        ?>
        <!-- Section 3 : Événements à venir -->
        <section id="evenements" class="container mx-auto px-4 py-12">
            <h2 class="text-3xl font-bold text-primary mb-6">Événements à venir</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Carte événement -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">Séminaire IA</h3>
                    <p class="text-gray-600 mb-4">Date et lieu de l’événement.</p>
                    <a href="#" class="text-secondary font-semibold hover:underline">Voir plus →</a>
                </div>
            </div>
            <!-- Pagination (placeholder) -->
            <div class="mt-8 flex justify-center space-x-2">
                <button class="px-4 py-2 bg-secondary text-white rounded hover:bg-secondary-dark transition">1</button>
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">2</button>
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">3</button>
            </div>
        </section>

        <?php
    }

    private function partenaires()
    {
        ?>
        <!-- Section 4 : Partenaires -->
        <section id="partenaires" class="bg-gray-100 py-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-primary mb-6">Nos partenaires</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-center">
                    <!-- Logo partenaire -->
                    <img src="/img/partenaire1.png" alt="Partenaire 1" class="mx-auto h-20 object-contain">
                    <img src="/img/partenaire2.png" alt="Partenaire 2" class="mx-auto h-20 object-contain">
                    <img src="/img/partenaire3.png" alt="Partenaire 3" class="mx-auto h-20 object-contain">
                    <img src="/img/partenaire4.png" alt="Partenaire 4" class="mx-auto h-20 object-contain">
                </div>
            </div>
        </section>
        <?php
    }

    private function main()
    {
        ?>
        <!-- Contenu Principal -->
        <main class="container mx-auto px-4 py-8">
            <?php $this->actualite(); ?>
            <?php $this->presentation_lab(); ?>
            <?php $this->evenements(); ?>
            <?php $this->partenaires(); ?>
        </main>
        <?php
    }
}