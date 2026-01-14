<?php
require_once __DIR__ . '/../views/pages/user/HomePage.php';
require_once __DIR__ . '/../models/Actualites.php';
require_once __DIR__ . '/../models/Equipes.php';
require_once __DIR__ . '/../models/Evenements.php';
require_once __DIR__ . '/../models/Partenaires.php';

class HomeController {
   
    public function index() {
        // get data
        $actualites = Actualites::getAll();
        $equipes = Equipes::getAll(include: ['membres']);
        $events = Evenements::getAll();
        $partenaires = Partenaires::getAll();

        // Load the view
        $view = new HomePage(data: [
            'actualites' => $actualites,
            'equipes' => $equipes,
            'events' => $events,
            'partenaires' => $partenaires
        ]);
        $view->render();
    }
}
