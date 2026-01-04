<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class ProjetsStatsPage extends AuthTemplate
{
    private $stats;
    public function __construct($title = 'Statistiques projets', array $data = [])
    {
        parent::__construct($title);
        $this->stats = $data['stats'] ?? [];
    }
    protected function content()
    {
        $stats = $this->stats;
        ?>
        <!-- html2pdf.js CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Statistiques sur les projets</h1>
                <button onclick="exportStatsPDF()" type="button" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Exporter en PDF</button>
            </div>
            <div id="stats-pdf-content">
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">Répartition par thématique</h2>
                <ul class="list-disc ml-6">
                    <?php foreach ($stats['par_thematique'] as $thematique => $count): ?>
                        <li><strong><?= htmlspecialchars($thematique) ?>:</strong> <?= $count ?> projet(s)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">Répartition par responsable</h2>
                <ul class="list-disc ml-6">
                    <?php foreach ($stats['par_responsable'] as $resp => $count): ?>
                        <li><strong><?= htmlspecialchars($resp) ?>:</strong> <?= $count ?> projet(s)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">Répartition par année</h2>
                <ul class="list-disc ml-6">
                    <?php foreach ($stats['par_annee'] as $annee => $projets): ?>
                        <li><strong><?= $annee ?>:</strong> <?= count($projets) ?> projet(s)
                            <ul class="ml-4 text-gray-700">
                                <?php foreach ($projets as $p): ?>
                                    <li><?= htmlspecialchars($p['titre']) ?> (
                                        <?= htmlspecialchars($p['date_debut']) ?> -
                                        <?php if ($p['date_fin'] != '0000-00-00'): ?>
                                            <?= htmlspecialchars($p['date_fin']) ?>
                                        <?php else: ?>
                                            En cours
                                        <?php endif; ?>
                                        )
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            </div> <!-- #stats-pdf-content -->
            <script>
            function exportStatsPDF() {
                const element = document.getElementById('stats-pdf-content');
                html2pdf()
                  .set({
                    margin: 0.5,
                    filename: 'statistiques_projets.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                  })
                  .from(element)
                  .save();
            }
            </script>
        </div>
        <?php
    }
}
