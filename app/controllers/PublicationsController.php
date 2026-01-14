<?php
require_once __DIR__ . '/../models/Publications.php';
require_once __DIR__ . '/../models/ProjetPublication.php';
require_once __DIR__ . '/../models/ProjetUser.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../views/pages/admin/PublicationsPage.php';
require_once __DIR__ . '/../views/pages/admin/SinglePublicationPage.php';
require_once __DIR__ . '/../views/pages/admin/CreatePublicationPage.php';
require_once __DIR__ . '/../views/pages/admin/EditPublicationPage.php';
require_once __DIR__ . '/../views/pages/admin/PendingPublicationsPage.php';

class PublicationsController
{
    // ------------- pages ------------- //
    // Liste de toutes les publications
    public function allPublicationsPage()
    {
        $publications = Publications::getAll(include: ['auteurs', 'projets']);
        $user = SessionManager::getUserData();
        $canWrite = in_array('publications.write', $user['permissions'] ?? []);
        $page = new PublicationsPage('Publications', [
            'publications' => $publications,
        ]);
        $page->render();
    }

    // Liste des publications en attente
    public function pendingPublicationsPage()
    {
        $publications = Publications::getAll(
            conditions: [['statut' => ['valeur' => 'en_attente']]],
            include: ['auteurs', 'projets']
        );
        
        $page = new PendingPublicationsPage('Publications en attente', [
            'publications' => $publications,
        ]);
        $page->render();
    }

    // Nouvelle publication
    public function newPublicationPage()
    {
        $user = SessionManager::getUserData();
        $page = new CreatePublicationPage('Nouvelle publication', [
            'user' => $user
        ]);
        $page->render();
    }


    // ------------- actions ------------- //
    // Créer une publication
    public function createPublication()
    {
        // verif and build $data
        $data = [];
        $fields = ['titre', 'resume', 'type', 'doi', 'annee', 'domaine', 'date_publication'];
        $required_fields = ['titre', 'type', 'annee', 'domaine', 'date_publication'];
        foreach ($fields as $f) {
            if (in_array($f, $required_fields) && empty($_POST[$f])) {
                SessionManager::setFlashMessage('error', 'Veuillez remplir tous les champs obligatoires.');
                header('Location: /admin/publications/new');
                exit;
            }
            $data[$f] = $_POST[$f] ?? '';
        }
        if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            SessionManager::setFlashMessage('error', 'Veuillez télécharger un fichier valide.');
            header('Location: /admin/publications/new');
            exit;
        }
        $data['statut'] = 'en_attente';
        $data['lien_telechargement'] = '';

        // check fichier
        $tmpName = $_FILES['fichier']['tmp_name'];
        $name = basename($_FILES['fichier']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'docx'];
        if (!in_array($ext, $allowed)) {
            SessionManager::setFlashMessage('error', 'Type de fichier non autorisé. Seuls les fichiers PDF et DOCX sont acceptés.');
            header('Location: /admin/publications/new');
            exit;
        }

        // make folder
        $folder = __DIR__ . '/../../public/uploads/publications/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true); // recursive
        }
        // upload file
        $dest = '/uploads/publications/' . uniqid('pub_') . '.' . $ext;
        $fullDest = __DIR__ . '/../../public' . $dest;
        if (move_uploaded_file($tmpName, $fullDest)) {
            $data['lien_telechargement'] = $dest;
        }

        // create pub
        $publication = Publications::create($data);

        // Lier l'auteur
        $user = SessionManager::getUserId();
        PublicationAuteur::create([
            'id_publication' => $publication['id_publication'],
            'id_user' => $user,
        ]);

        // fin
        SessionManager::setFlashMessage('success', 'Publication soumise pour validation.');
        header('Location: /admin/publications');
        exit;
    }

    // Détail d'une publication
    public function singlePublicationPage($id_publication)
    {
        $publication = Publications::getUnique(
            conditions: ['id_publication' => ['valeur' => $id_publication]],
            include: ['auteurs', 'projets']
        );
        if (!$publication) {
            SessionManager::setFlashMessage('error', 'Publication introuvable.');
            header('Location: /admin/publications');
            exit;
        }
        $user = SessionManager::getUserData();
        $canEdit = in_array('publications.write', $user['permissions'] ?? []) || in_array($user['id_user'], array_column($publication['auteurs'] ?? [], 'id_user'));
        $page = new SinglePublicationPage('Publication', [
            'publication' => $publication,
        ]);
        $page->render();
    }

    // Editer une publication
    public function editPublicationPage($id_publication)
    {
        // get pub
        $publication = Publications::getUnique(
            conditions: ['id_publication' => ['valeur' => $id_publication]],
            include: ['auteurs', 'projets']
        );
        if (!$publication) {
            SessionManager::setFlashMessage('error', 'Publication introuvable.');
            header('Location: /admin/publications');
            exit;
        }

        // if has write perm or he's author
        $canWrite = SessionManager::hasPermissions(['publications.write']);
        $isAuthor = in_array(SessionManager::getUserId(), array_column($publication['auteurs'], 'id_user'));
        if (!$canWrite && !$isAuthor) {
            SessionManager::setFlashMessage('error', 'Accès refusé.');
            header('Location: /admin/publications');
            exit;
        }

        // projets to be added
        $projets = SessionManager::hasPermissions(['publications.write'])
            ? Projets::getAll()
            : Users::getUnique(
            conditions: ['id_user' => ['valeur' => SessionManager::getUserId()]],
            include: ['projets']
            )['projets'];

        // projets of user
        $projets_user = ProjetUser::getAll(
            conditions: ['id_user' => ['valeur' => SessionManager::getUserId()]]
        );

        $page = new EditPublicationPage('Modifier la publication', [
            'publication' => $publication,
            'users' => Users::getAll(),
            'projets' => $projets,
            'user_projets_ids' => array_column($projets_user, 'id_projet'),
        ]);
        $page->render();
    }

    // Mettre à jour une publication
    public function updatePublication($id_publication)
    {
        // permissions
        if (
            !SessionManager::hasPermissions(['publications.write'])
            && !Publications::isAuthor($id_publication, SessionManager::getUserId())
        ) {
            SessionManager::setFlashMessage('error', 'Action non autorisée.');
            header('Location: /admin/publications');
            exit;
        }

        // check fields
        $fields = ['titre', 'resume', 'type', 'doi', 'annee', 'domaine', 'date_publication'];
        $required_fields = ['titre', 'type', 'doi', 'annee', 'domaine', 'date_publication'];
        $data = [];
        foreach ($fields as $f) {
            if (empty($_POST[$f]) && in_array($f, $required_fields)) {
                SessionManager::setFlashMessage('error', 'Veuillez remplir tous les champs obligatoires.');
                header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
                exit;
            }
            $data[$f] = $_POST[$f] ?? '';
        }

        // Upload fichier
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['fichier']['tmp_name'];
            $name = basename($_FILES['fichier']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['pdf', 'docx'];
            if (in_array($ext, $allowed)) {
                $dest = '/public/uploads/publications/' . uniqid('pub_') . '.' . $ext;
                $fullDest = __DIR__ . '/../../..' . $dest;
                if (move_uploaded_file($tmpName, $fullDest)) {
                    // Supprimer l'ancien fichier si existant
                    $old = Publications::getUnique(conditions: ['id_publication' => ['valeur' => $id_publication]]);
                    if ($old && !empty($old['lien_telechargement'])) {
                        $oldPath = __DIR__ . '/../../public' . $old['lien_telechargement'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $data['lien_telechargement'] = $dest;
                }
            }
        }

        // edit
        Publications::edit($data, 'id_publication', $id_publication);
        SessionManager::setFlashMessage('success', 'Publication modifiée.');
        header('Location: /admin/publications/' . urlencode($id_publication));
        exit;
    }

    // Supprimer une publication
    public function deletePublication($id_publication)
    {
        $user = SessionManager::getUserData();
        $publication = Publications::getUnique(conditions: ['id_publication' => ['valeur' => $id_publication]]);
        if (!$publication) {
            SessionManager::setFlashMessage('error', 'Publication introuvable.');
            header('Location: /admin/publications');
            exit;
        }

        // permissions
        $canWrite = SessionManager::hasPermissions(['publications.write']);
        $isAuthor = in_array($user['id_user'], array_column($publication['auteurs'] ?? [], 'id_user'));
        if (!$canWrite && !$isAuthor) {
            SessionManager::setFlashMessage('error', 'Action non autorisée.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }

        // Supprimer le fichier associé
        if (!empty($publication['lien_telechargement'])) {
            $filePath = __DIR__ . '/../../public' . $publication['lien_telechargement'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // supp publication
        Publications::delete([
            'id_publication' => ['valeur' => $id_publication]
        ]);
        SessionManager::setFlashMessage('success', 'Publication supprimée.');
        header('Location: /admin/publications');
        exit;
    }

    // Ajouter un auteur à une publication
    public function addAuteur($id_publication)
    {
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Aucun utilisateur sélectionné.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }
        // Vérifier si déjà auteur
        $exists = PublicationAuteur::getUnique(conditions: [
            'id_publication' => ['valeur' => $id_publication],
            'id_user' => ['valeur' => $id_user],
        ]);
        if ($exists) {
            SessionManager::setFlashMessage('error', 'Cet utilisateur est déjà auteur.');
        } else {
            PublicationAuteur::create([
                'id_publication' => $id_publication,
                'id_user' => $id_user,
            ]);
            SessionManager::setFlashMessage('success', 'Auteur ajouté.');
        }
        header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
        exit;
    }

    // Ajouter un projet à une publication
    public function addProjet($id_publication)
    {
        $id_projet = $_POST['id_projet'] ?? null;
        if (!$id_projet) {
            SessionManager::setFlashMessage('error', 'Aucun projet sélectionné.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }
        // Vérifier si déjà lié
        $exists = ProjetPublication::getUnique(conditions: [
            'id_publication' => ['valeur' => $id_publication],
            'id_projet' => ['valeur' => $id_projet],
        ]);
        if ($exists) {
            SessionManager::setFlashMessage('error', 'Ce projet est déjà lié à la publication.');
        } else {
            ProjetPublication::create([
                'id_publication' => $id_publication,
                'id_projet' => $id_projet,
            ]);
            SessionManager::setFlashMessage('success', 'Projet lié à la publication.');
        }
        header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
        exit;
    }

    // Retirer un auteur d'une publication
    public function removeAuteur($id_publication)
    {
        // field
        $id_user = $_POST['id_user'] ?? null;
        if (!$id_user) {
            SessionManager::setFlashMessage('error', 'Utilisateur non spécifié.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }

        // permissions
        $user = Users::getUnique(
            conditions: ['id_user' => ['valeur' => SessionManager::getUserId()]],
            include: ['publications']
        );
        $canWrite = SessionManager::hasPermissions(['publications.write']);
        if (!$canWrite && $user['id_user'] != $id_user) {
            SessionManager::setFlashMessage('error', 'Action non autorisée.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }

        // delete
        PublicationAuteur::delete([
            'id_publication' => ['valeur' => $id_publication],
            'id_user' => ['valeur' => $id_user],
        ]);
        SessionManager::setFlashMessage('success', 'Auteur retiré.');
        header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
        exit;
    }

    // Retirer un projet d'une publication
    public function removeProjet($id_publication)
    {
        // field
        $id_projet = $_POST['id_projet'] ?? null;
        if (!$id_projet) {
            SessionManager::setFlashMessage('error', 'Projet non spécifié.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }

        // permissions
        $user = Users::getUnique(
            conditions: ['id_user' => ['valeur' => SessionManager::getUserId()]],
            include: ['projets']
        );
        $canWrite = SessionManager::hasPermissions(['publications.write']);
        $userProjets = array_column($user['projets'] ?? [], 'id_projet');
        if (!$canWrite && !in_array($id_projet, $userProjets)) {
            SessionManager::setFlashMessage('error', 'Action non autorisée.');
            header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
            exit;
        }

        // supprimer
        ProjetPublication::delete([
            'id_publication' => ['valeur' => $id_publication],
            'id_projet' => ['valeur' => $id_projet],
        ]);
        SessionManager::setFlashMessage('success', 'Projet retiré.');
        header('Location: /admin/publications/' . urlencode($id_publication) . '/edit');
        exit;
    }

    // Accepter une publication
    public function acceptPublication($id_publication)
    {
        Publications::edit(['statut' => 'valide'], 'id_publication', $id_publication);
        SessionManager::setFlashMessage('success', 'Publication acceptée.');
        header('Location: /admin/publications/pending');
        exit;
    }

    // Refuser une publication
    public function refusePublication($id_publication)
    {
        Publications::edit(['statut' => 'rejete'], 'id_publication', $id_publication);
        SessionManager::setFlashMessage('success', 'Publication refusée.');
        header('Location: /admin/publications/pending');
        exit;
    }
}
