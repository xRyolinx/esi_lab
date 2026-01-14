<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/Diaporama.php';
require_once __DIR__ . '/../../components/Card.php';
require_once __DIR__ . '/../../components/Organigramme.php';

class HomePage extends MainTemplate
{
    private array $actualites;
    private array $equipes;
    private array $events;
    private array $partenaires;

    public function __construct($title = "Accueil - ESI LAB", $data = [])
    {
        parent::__construct($title);
        $this->actualites = $data['actualites'] ?? [];
        $this->equipes = $data['equipes'] ?? [];
        $this->events = $data['events'] ?? [];
        $this->partenaires = $data['partenaires'] ?? [];

        // js
        $this->addJS('/js/pagination_events.js');
        $this->addJS('/js/pagination_equipes.js');
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
                'img' => '/img/projets.jpg'
            ],
            [
                'text' => 'Découvrez nos publications récentes',
                'link' => '/publications',
                'img' => '/img/publications.jpg'
            ],
            [
                'text' => 'Découvrez nos derniers événements',
                'link' => '/evenements',
                'img' => '/img/events.jpg'
            ],
            [
                'text' => 'Découvrez les soutenances prévues',
                'link' => '/actualites?type=soutenance',
                'img' => '/img/soutenances.jpg'
            ]
        ]);
        $diapo->render();
    }

    private function actualite()
    {
        ?>
        <!-- Section 1 : Actualités scientifiques -->
        <section id="actualites" class="container mx-auto px-4 pb-12">
            <h2 class="text-3xl font-bold text-primary mb-6 text-center">Actualités scientifiques</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($this->actualites)): ?>
                    <div class="col-span-3 text-center text-gray-400">Aucune actualité disponible.</div>
                <?php else: ?>
                    <?php $max = 3;
                    $count = count($this->actualites);
                    $shown = 0; ?>
                    <?php foreach ($this->actualites as $a): ?>
                        <?php if ($shown++ >= $max)
                            break; ?>
                        <?php
                        ob_start();
                        ?>
                        <p class="text-gray-600 mb-4">
                            <?= htmlspecialchars($a['resume'] ?? $a['description'] ?? '') ?>
                        </p>
                        <div class="mb-2 text-xs text-blue-700 font-semibold">
                            <?= htmlspecialchars($a['type'] ?? '') ?>
                            <?php if (!empty($a['date_publication'])): ?>
                                &bull; <?= htmlspecialchars($a['date_publication']) ?>
                            <?php endif; ?>
                        </div>
                        <?php
                        $content = ob_get_clean();
                        $footer = '<a href="/actualites/' . urlencode($a['id_actualite']) . '" class="text-secondary font-semibold hover:underline">Voir le détail →</a>';
                        $card = new Card(
                            $a['titre'],
                            $content,
                            $footer,
                            [
                                'class' => 'bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between h-full'
                            ]
                        );
                        $card->render();
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (!empty($this->actualites) && count($this->actualites) > 3): ?>
                <div class="mt-8 flex justify-center">
                    <a href="/actualites" class="text-xl font-bold text-primary underline hover:text-primary-dark transition">Voir
                        toutes les actualités</a>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }

    private function presentation_lab()
    {
        ?>
        <!-- Section 2 : Présentation du laboratoire et organigramme -->
        <section id="presentation" class="bg-white py-12">
            <div class="container mx-auto px-4 text-center">
                <!-- labo -->
                <h2 class="text-3xl font-bold text-primary mb-6">À propos du laboratoire</h2>
                <p class="text-gray-700 max-w-3xl mx-auto mb-8">
                    Le laboratoire de l’École Supérieure d’Informatique (ESI) 
                    est dédié à la recherche et au développement dans les 
                    domaines de l’intelligence artificielle, des systèmes 
                    distribués, de la cybersécurité et des technologies de 
                    l’information. Il regroupe enseignants, chercheurs et 
                    étudiants autour de projets innovants et collaboratifs.
                </p>

                <!-- diagramme de sequipes -->
                <h3 class="text-2xl font-bold text-secondary mb-4 mt-10">Équipes</h3>
                <?php
                $perPage = 3;
                $total = count($this->equipes);
                $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
                ?>
                <div id="organigramme-paginate" class="flex flex-col items-center">
                    <?php if ($total === 0): ?>
                        <div class="text-gray-400">Aucune équipe enregistrée.</div>
                    <?php else: ?>
                        <!-- equipes -->
                        <?php for ($p = 0; $p < $totalPages; $p++): ?>
                            <div class="org-diagram-page" data-page="<?= $p + 1 ?>" style="<?= $p === 0 ? '' : 'display:none;' ?>">
                                <div class="flex gap-8">
                                    <?php for ($i = $p * $perPage; $i < min($total, ($p + 1) * $perPage); $i++): ?>
                                        <?php $org = new Organigramme($this->equipes[$i]);
                                        $org->render(); ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <!-- pagination -->
                        <div class="mt-6 flex justify-center space-x-2">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <button type="button"
                                    class="org-page-btn px-4 py-2 <?= $i === 1 ? 'bg-primary-dark text-white font-bold' : 'bg-gray-200 text-gray-700' ?> rounded hover:bg-primary hover:text-white transition"
                                    data-page="<?= $i ?>"> <?= $i ?> </button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }

    private function evenements()
    {
        ?>
        <!-- Section 3 : Événements à venir (cartes + pagination JS) -->
        <section id="evenements" class="container mx-auto px-4 py-12">
            <h2 class="text-3xl font-bold text-center text-primary mb-6">Événements à venir</h2>
            <?php $perPage = 3;
            $total = count($this->events);
            $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1; ?>
            <div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($this->events)): ?>
                    <div class="col-span-3 text-center text-gray-400">Aucun événement à venir.</div>
                <?php else: ?>
                    <?php
                    $idx = 0;
                    foreach ($this->events as $e):
                        ob_start();
                        ?>
                        <p class="text-gray-600 mb-4">
                            <?= htmlspecialchars($e['lieu'] ?? '') ?>
                            <?php if (!empty($e['date_debut'])): ?>
                                <br><span class="text-xs text-blue-700 font-semibold">Le
                                    <?= htmlspecialchars($e['date_debut']) ?>                     <?php if (!empty($e['date_fin'])): ?> au
                                        <?= htmlspecialchars($e['date_fin']) ?>                     <?php endif; ?></span>
                            <?php endif; ?>
                        </p>
                        <?php
                        $content = ob_get_clean();
                        $footer = '<a href="/evenements/' . urlencode($e['id_evenement']) . '" class="text-secondary font-semibold hover:underline">Voir plus →</a>';
                        $card = new Card(
                            $e['titre'],
                            $content,
                            $footer,
                            [
                                'class' => 'event-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between h-full',
                                'data-idx' => $idx,
                                'style' => ($idx < $perPage ? 'display:flex;' : 'display:none;')
                            ]
                        );
                        $card->render();
                        $idx++;
                    endforeach;
                    ?>
                <?php endif; ?>
            </div>
            <?php if ($totalPages > 1): ?>
                <div id="events-pagination" class="mt-8 flex justify-center space-x-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <button type="button"
                            class="evt-page-btn px-4 py-2 <?= $i === 1 ? 'bg-primary-dark text-white font-bold' : 'bg-gray-200 text-gray-700' ?> rounded hover:bg-primary hover:text-white transition"
                            data-page="<?= $i ?>"> <?= $i ?> </button>
                    <?php endfor; ?>
                </div>
                <?= $this->script_pagination_events(); ?>
            <?php endif; ?>
        </section>
        <?php
    }

    private function script_pagination_events()
    {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const perPage = 3;
                const eventCards = document.querySelectorAll('.event-card');
                const totalPages = Math.ceil(eventCards.length / perPage);
                const paginationContainer = document.getElementById('events-pagination');

                function showPage(page) {
                    eventCards.forEach((card, index) => {
                        card.style.display = (index >= (page - 1) * perPage && index < page * perPage) ? 'flex' : 'none';
                    });
                }

                paginationContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('evt-page-btn')) {
                        const page = parseInt(e.target.getAttribute('data-page'));
                        showPage(page);

                        // Update button styles
                        document.querySelectorAll('.evt-page-btn').forEach(btn => {
                            btn.classList.remove('bg-primary-dark', 'text-white', 'font-bold');
                            btn.classList.add('bg-gray-200', 'text-gray-700');
                        });
                        e.target.classList.add('bg-primary-dark', 'text-white', 'font-bold');
                        e.target.classList.remove('bg-gray-200', 'text-gray-700');
                    }
                });

                // Initial display
                showPage(1);
            });
        </script>
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
                    <?php if (empty($this->partenaires)): ?>
                        <div class="col-span-4 text-center text-gray-400">Aucun partenaire enregistré.</div>
                    <?php else: ?>
                        <?php $max = 3;
                        $count = count($this->partenaires);
                        $shown = 0; ?>
                        <?php foreach ($this->partenaires as $p): ?>
                            <?php if ($shown++ >= $max)
                                break; ?>
                            <div class="flex flex-col items-center">
                                <img src="<?= htmlspecialchars($p['logo'] ?? '/img/partenaire-placeholder.png') ?>"
                                    alt="<?= htmlspecialchars($p['nom']) ?>" class="mx-auto h-20 object-contain mb-2" />
                                <span
                                    class="block text-center text-primary font-semibold text-base"><?= htmlspecialchars($p['nom']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if (!empty($this->partenaires) && count($this->partenaires) > 3): ?>
                    <div class="mt-8 flex justify-center">
                        <a href="/partenaires"
                            class="text-xl font-bold text-primary underline hover:text-primary-dark transition">Voir tous les
                            partenaires</a>
                    </div>
                <?php endif; ?>
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