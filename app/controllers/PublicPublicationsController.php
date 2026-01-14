<?php
require_once __DIR__ . '/../models/Publications.php';
require_once __DIR__ . '/../views/pages/user/PublicationsPage.php';
require_once __DIR__ . '/../views/pages/user/SinglePublicationPage.php';

class PublicPublicationsController
{
    // Liste des publications
    public function index()
    {
        // Récupérer les filtres depuis GET
        $conditions = [
            'statut' => ['valeur' => 'valide'],
        ];
        if (!empty($_GET['type'])) {
            $conditions['type'] = $_GET['type'];
        }
        if (!empty($_GET['domaine'])) {
            $conditions['domaine'] = $_GET['domaine'];
        }
        if (!empty($_GET['annee'])) {
            $conditions['annee'] = $_GET['annee'];
        }

        $publications = Publications::getAll(
            conditions: [$conditions],
            include: ['auteurs', 'projets']
        );
        $page = new PublicationsPage('Liste des publications', [
            'publications' => $publications
        ]);
        $page->render();
    }

    // Détail d'une publication
    public function singlePublication($id_publication)
    {
        $publication = Publications::getUnique(
            conditions: [['id_publication' => ['valeur' => $id_publication]]],
            include: ['auteurs', 'projets']
        );
        if (!$publication) {
            http_response_code(404);
            echo "Publication introuvable.";
            return;
        }
        $page = new SinglePublicationPage('Détail publication', [
            'publication' => $publication
        ]);
        $page->render();
    }
}
