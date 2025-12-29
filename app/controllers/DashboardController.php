<?php
require_once __DIR__ . '/../views/pages/admin/DashboardPage.php';

class DashboardController {
   
    public function index() {
        // Load the view
        $view = new DashboardPage();
        $view->render();
    }
}
