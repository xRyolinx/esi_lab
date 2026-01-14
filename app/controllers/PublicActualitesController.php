<?php
require_once __DIR__ . '/../models/Actualites.php';
require_once __DIR__ . '/../views/pages/user/ActualitesPage.php';

class PublicActualitesController
{
    public function index()
    {
        $filters = [];
        if (!empty($_GET['type'])) {
            $filters['type'] = ['valeur' => $_GET['type']];
        }
        $conditions = [];
        if (!empty($filters)) {
            $conditions[] = $filters;
        }
        $actualites = Actualites::getAll(
            conditions: $conditions
        );
        $page = new ActualitesPage('Actualités', [
            'actualites' => $actualites
        ]);
        $page->render();
    }
}
