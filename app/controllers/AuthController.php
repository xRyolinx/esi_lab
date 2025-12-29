<?php
require_once __DIR__ . '/../views/pages/user/LoginPage.php';
require_once __DIR__ . '/../views/pages/user/RegisterPage.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Roles.php';
require_once __DIR__ . '/../config/SessionManager.php';

class AuthController
{

    // ------------ pages ------------
    public function loginPage()
    {
        // Load the view
        $view = new LoginPage();
        $view->render();
    }

    public function registerPage()
    {
        // get roles
        $roles = Roles::getAll();

        // Load the view
        $view = new RegisterPage("Inscription - ESI LAB", ['roles' => $roles]);
        $view->render();
    }


    // ------------ action ------------
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($password)) {
            SessionManager::setFlashMessage('error', 'Veuillez remplir tous les champs.');
            header("Location: /login");
            exit;
        }

        $user = Users::getUnique(conditions: [['username' => ['valeur' => $username]]]);
        if (!$user) {
            SessionManager::setFlashMessage('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
            header("Location: /login");
            exit;
        }
        if (!password_verify($password, $user['password'])) {
            SessionManager::setFlashMessage('error', 'Email ou mot de passe incorrect.');
            header("Location: /login");
            exit;
        }
        $user_protected = Users::hide($user);
        SessionManager::login($user_protected);
        SessionManager::setFlashMessage('success', 'Connexion réussie.');
        header('Location: /');
        exit;
    }

    public function register()
    {
        $fields = ['nom', 'prenom', 'email', 'username', 'password', 'grade', 'domaine_recherche', 'biographie', 'role'];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $_POST[$field] ?? '';
        }
        // Validation basique
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                SessionManager::setFlashMessage('error', 'Veuillez remplir tous les champs obligatoires.');
                header('Location: /register');
                exit;
            }
        }
        // Vérifier unicité email et username
        if (Users::getUnique(conditions: [['email' => ['valeur' => $data['email']]]])) {
            SessionManager::setFlashMessage('error', 'Cet email est déjà utilisé.');
            header('Location: /register');
            exit;
        }
        if (Users::getUnique(conditions: [['username' => ['valeur' => $data['username']]]])) {
            SessionManager::setFlashMessage('error', 'Ce nom d\'utilisateur est déjà pris.');
            header('Location: /register');
            exit;
        }
        // Hash du mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['statut'] = 'actif';
        $data['date_creation'] = date('Y-m-d H:i:s');
        $data['photo'] = null;
        $data['id_equipe'] = null;
        try {
            Users::create($data);
            SessionManager::setFlashMessage('success', 'Inscription réussie, vous pouvez vous connecter.');
            header('Location: /login');
            exit;
        } catch (Exception $e) {
            SessionManager::setFlashMessage('error', "Erreur lors de l'inscription : " . $e->getMessage());
            header('Location: /register');
            exit;
        }
    }

    public function logout() {
        SessionManager::logout();
        SessionManager::setFlashMessage('success','Déconnexion réussie.');
        header('Location: /login');
        exit;
    }
}
