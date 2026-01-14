<?php
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Roles.php';
require_once __DIR__ . '/../models/Equipes.php';
require_once __DIR__ . '/../views/pages/admin/UsersPage.php';
require_once __DIR__ . '/../views/pages/admin/CreateUserPage.php';

require_once __DIR__ . '/../views/pages/admin/ProfilePage.php';
require_once __DIR__ . '/../views/pages/admin/EditProfilePage.php';


class UsersController
{
    // -------------------- pages ----------------------
    // Page de liste des users
    public function allUsersPage()
    {
        // get data
        $users = Users::getAll(include: ['equipe', 'publications']);
        foreach ($users as &$user) {
            $user['nb_pubs'] = count($user['publications'] ?? []);
            unset($user['publications']);
        }
        $roles = Roles::getAll();
        $equipes = Equipes::getAll();

        // get filters
        $f_search = $_GET['search'] ?? null;
        $f_role = $_GET['role'] ?? null;
        $f_grade = $_GET['grade'] ?? null;
        $f_statut = $_GET['statut'] ?? null;
        $f_id_equipe = $_GET['id_equipe'] ?? null;
        $f_nb_pubs = $_GET['pubs'] ?? null;

        // filter users
        $filteredUsers = [];
        foreach ($users as &$user) {
            $show = true;

            // recherche
            if (!empty($f_search)) {
                $s = mb_strtolower(trim($f_search));
                $show = (
                    mb_strpos(mb_strtolower($user['nom']), $s) !== false ||
                    mb_strpos(mb_strtolower($user['prenom']), $s) !== false ||
                    mb_strpos(mb_strtolower($user['username']), $s) !== false ||
                    mb_strpos(mb_strtolower($user['email']), $s) !== false
                );
            }

            // role
            if ($show && !empty($f_role) && $user['role'] != $f_role)
                $show = false;

            // grade
            if ($show && !empty($f_grade) && $user['grade'] != $f_grade)
                $show = false;

            // statut
            if ($show && !empty($f_statut) && $user['statut'] != $f_statut)
                $show = false;

            // equipe
            if ($show && !empty($f_id_equipe) && $user['id_equipe'] != $f_id_equipe)
                $show = false;

            // Publications
            if ($show && !empty($f_nb_pubs)) {
                $pubCount = $user['nb_pubs'];
                if ($f_nb_pubs == '0' && $pubCount != 0)
                    $show = false;
                if ($f_nb_pubs == '1-5' && ($pubCount < 1 || $pubCount > 5))
                    $show = false;
                if ($f_nb_pubs == '6-10' && ($pubCount < 6 || $pubCount > 10))
                    $show = false;
                if ($f_nb_pubs == '11+' && $pubCount < 11)
                    $show = false;
            }

            // check
            if ($show) {
                $filteredUsers[] = $user;
            }
        }

        // afficher
        $page = new UsersPage('Gestion des utilisateurs', ['users' => $filteredUsers, 'roles' => $roles, 'equipes' => $equipes]);
        $page->render();
    }

    // Page d'un seul user
    public function singleUserPage($id_user)
    {
        $user = Users::getUnique(conditions: [['id_user' => ['valeur' => $id_user]]]);
        if (!$user) {
            SessionManager::setFlashMessage('error', "Utilisateur introuvable.");
            header('Location: /admin/users');
            exit;
        }

        $roles = Roles::getAll();
        $equipes = Equipes::getAll();
        require_once __DIR__ . '/../views/pages/admin/SingleUserPage.php';
        $page = new SingleUserPage('Utilisateur', [
            'user' => $user,
            'roles' => $roles,
            'equipes' => $equipes,
        ]);
        $page->render();
    }

    // Page de création de user
    public function createUserPage()
    {
        $roles = Roles::getAll();
        $equipes = Equipes::getAll();
        $page = new CreateUserPage('Créer un utilisateur', [
            'roles' => $roles,
            'equipes' => $equipes,
        ]);
        $page->render();
    }

    // Page de profil utilisateur (profil connecté)
    public function profilePage()
    {
        // On suppose que l'ID utilisateur est stocké en session
        $user = SessionManager::getUserData();
        $page = new ProfilePage('Mon profil', ['user' => $user]);
        $page->render();
    }

    // Page d'édition du profil utilisateur (profil connecté)
    public function editProfilePage()
    {
        $user = SessionManager::getUserData();
        $page = new EditProfilePage('Modifier mon profil', ['user' => $user]);
        $page->render();
    }


    // -------------------- actions ----------------------
    // creer user
    public function createUser()
    {
        $fields = ['nom', 'prenom', 'email', 'username', 'poste', 'password', 'role', 'statut', 'grade', 'domaine_recherche', 'biographie', 'id_equipe'];
        $notRequired = ['grade', 'domaine_recherche', 'biographie', 'id_equipe'];
        $check = true;
        foreach ($fields as $f) {
            if (!in_array($f, $notRequired) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $check = false;
            SessionManager::setFlashMessage('error', 'Email invalide.');
        }
        // Username unique check
        if (Users::getUnique(conditions: [['username' => ['valeur' => $_POST['username']]]])) {
            $check = false;
            SessionManager::setFlashMessage('error', "Ce nom d'utilisateur existe déjà.");
        }
        // Email unique check
        if (Users::getUnique(conditions: [['email' => ['valeur' => $_POST['email']]]])) {
            $check = false;
            SessionManager::setFlashMessage('error', "Cet email existe déjà.");
        }

        // if any err
        if (!$check) {
            header('Location: /admin/users/new');
            exit;
        }

        // creer user
        if (empty($_POST['id_equipe'])) {
            $_POST['id_equipe'] = null;
        }
        $user = [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'username' => $_POST['username'],
            'poste' => $_POST['poste'] ?? '',
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $_POST['role'],
            'statut' => $_POST['statut'],
            'id_equipe' => $_POST['id_equipe'] ?? null,
            'grade' => $_POST['grade'] ?? '',
            'domaine_recherche' => $_POST['domaine_recherche'] ?? '',
            'biographie' => $_POST['biographie'] ?? '',
            'photo' => '/img/default_profile_picture.jpg',
            'date_creation' => date('Y-m-d H:i:s'),
        ];
        Users::create($user);

        header('Location: /admin/users');
        exit;
    }

    // Modifier un user
    public function updateUser($id_user)
    {
        $fields = ['nom', 'prenom', 'email', 'username', 'poste', 'role', 'statut', 'grade', 'domaine_recherche', 'biographie', 'photo'];
        $notRequired = ['grade', 'domaine_recherche', 'biographie', 'photo'];
        $check = true;
        foreach ($fields as $f) {
            if (!in_array($f, $notRequired) && empty($_POST[$f])) {
                $check = false;
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
            }
        }
        if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $check = false;
            SessionManager::setFlashMessage('error', 'Email invalide.');
        }
        // Username unique check (ignore self)
        $existing = Users::getUnique(conditions: [['username' => ['valeur' => $_POST['username']]]]);
        if ($existing && $existing['id_user'] != $id_user) {
            $check = false;
            SessionManager::setFlashMessage('error', "Ce nom d'utilisateur existe déjà.");
        }
        // Email unique check (ignore self)
        $existing = Users::getUnique(conditions: [['email' => ['valeur' => $_POST['email']]]]);
        if ($existing && $existing['id_user'] != $id_user) {
            $check = false;
            SessionManager::setFlashMessage('error', "Cet email existe déjà.");
        }
        if (!$check) {
            header('Location: /admin/users/' . urlencode($id_user));
            exit;
        }

        // update user
        $user = [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'username' => $_POST['username'],
            'poste' => $_POST['poste'] ?? '',
            'role' => $_POST['role'],
            'statut' => $_POST['statut'],
            'grade' => $_POST['grade'] ?? '',
            'domaine_recherche' => $_POST['domaine_recherche'] ?? '',
            'biographie' => $_POST['biographie'] ?? '',
            'photo' => $_POST['photo'] ?? '',
        ];
        Users::edit($user, 'id_user', $id_user);

        SessionManager::setFlashMessage('success', 'Utilisateur mis à jour.');
        header('Location: /admin/users/' . urlencode($id_user));
        exit;
    }

    // Supprimer un user
    public function deleteUser($id_user)
    {
        Users::delete([
            ['id_user' => ['valeur' => $id_user]]
        ]);
        SessionManager::setFlashMessage('success', 'Utilisateur supprimé.');
        header('Location: /admin/users');
        exit;
    }


    // Action de modification du profil utilisateur
    public function handleEditProfile()
    {
        // check fields
        $fields = ['nom', 'prenom', 'email', 'username', 'poste', 'grade', 'domaine_recherche', 'biographie'];
        $required_fields = ['nom', 'prenom', 'email', 'username'];
        foreach ($required_fields as $f) {
            if (empty($_POST[$f] ?? '')) {
                SessionManager::setFlashMessage('error', "Le champ '$f' est requis.");
                header('Location: /admin/profile/edit');
                exit;
            }
        }

        // data
        $userData = [];
        foreach ($fields as $f) {
            $userData[$f] = $_POST[$f] ?? '';
        }
        $id_user = SessionManager::getUserId();

        // check if email OR username taken
        $results = Users::getAll(conditions: [
            ['email' => $userData['email']],
            ['username' => $userData['username']],
        ]);
        if (
            (count($results) == 1 && $results[0]['id_user'] != $id_user)
            || count($results) > 1
        ) {
            $email_setFlash = false;
            $username_setFlash = false;

            foreach ($results as $res) {
                if (!$email_setFlash && $res['email'] == $userData['email'] && $res['id_user'] != $id_user) {
                    SessionManager::setFlashMessage('error', "Cet email est déjà utilisé.");
                    $email_setFlash = true;
                }
                if (!$username_setFlash && $res['username'] == $userData['username'] && $res['id_user'] != $id_user) {
                    SessionManager::setFlashMessage('error', "Ce nom d'utilisateur est déjà utilisé.");
                    $username_setFlash = true;
                }
            }
            header('Location: /admin/profile/edit');
            exit;
        }


        // Gestion de la photo
        // if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        //     $tmpName = $_FILES['photo']['tmp_name'];
        //     $name = basename($_FILES['photo']['name']);
        //     $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        //     $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        //     if (in_array($ext, $allowed)) {
        //         $dest = '/public/img/profiles/' . uniqid('user_') . '.' . $ext;
        //         $fullDest = __DIR__ . '/../../..' . $dest;
        //         if (move_uploaded_file($tmpName, $fullDest)) {
        //             $userData['photo'] = $dest;
        //         }
        //     }
        // }

        // edit
        Users::edit($userData, 'id_user', $id_user);

        // Mettre à jour la session si besoin
        $user = Users::getUnique(conditions: [['id_user' => ['valeur' => $id_user]]]);
        SessionManager::setUser($user);
        SessionManager::setFlashMessage('success', 'Profil mis à jour.');
        header('Location: /admin/profile');
        exit;
    }

    // Action de modification de la photo de profil
    public function handleProfilePhoto()
    {
        // check if photo sent
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            SessionManager::setFlashMessage('error', 'Aucune photo sélectionnée.');
            header('Location: /admin/profile/edit');
            exit;
        }

        // check format
        $id_user = SessionManager::getUserId();
        $tmpName = $_FILES['photo']['tmp_name'];
        $name = basename($_FILES['photo']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            SessionManager::setFlashMessage('error', 'Format de fichier non supporté.');
            header('Location: /admin/profile/edit');
            exit;
        }


        // create folder
        $uploadDir = __DIR__ . '/../../public/img/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // recursive
        }
        
        // upload
        $dest = '/img/profiles/' . uniqid('user_') . '.' . $ext;
        $fullDest = __DIR__ . '/../../public' . $dest;
        if (move_uploaded_file($tmpName, $fullDest)) {
            Users::edit(['photo' => $dest], 'id_user', $id_user);
            $user = Users::getUnique(conditions: [['id_user' => ['valeur' => $id_user]]]);
            SessionManager::setUser($user);
            SessionManager::setFlashMessage('success', 'Photo de profil mise à jour.');
        } else {
            SessionManager::setFlashMessage('error', 'Erreur lors de l\'upload de la photo.');
        }

        header('Location: /admin/profile/edit');
        exit;
    }

}
