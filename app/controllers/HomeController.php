<?php
require_once __DIR__ . '/../views/pages/user/HomePage.php';

class HomeController {
   
    public function index() {
        // Load the view
        $view = new HomePage();
        $view->render();
    }
}
