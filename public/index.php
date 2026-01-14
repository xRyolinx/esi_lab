<?php
session_start();
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/config/SessionManager.php';
require_once __DIR__ . '/../app/core/Middlewares.php';

$router = new Router();


// --------------- user routes ---------------
$router->get('/', 'HomeController:index', [
    Middlewares::refreshUser(),
]);

$router->get('/admin/profile', 'UsersController:profilePage', [
    Middlewares::requireAuthPage(),
]);

$router->get('/admin/profile/edit', 'UsersController:editProfilePage', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/profile/edit', 'UsersController:handleEditProfile', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/profile/photo', 'UsersController:handleProfilePhoto', [
    Middlewares::requireAuthPage(),
]);


// --------------- auth routes ---------------
$router->get('/register', 'AuthController:registerPage');
$router->post('/register', 'AuthController:register');

$router->get('/login', 'AuthController:loginPage');
$router->post('/login', 'AuthController:login');

$router->get('/logout', 'AuthController:logout');
$router->post('/logout', 'AuthController:logout');


// --------------- admin routes ---------------
// dashboard
$router->get('/admin/dashboard', 'DashboardController:index', [
    Middlewares::requireAuthPage(),
]);


// users
$router->get('/admin/users', 'UsersController:allUsersPage', [
    Middlewares::requirePermissionsPage(['users.read']),
]);
$router->get('/admin/users/new', 'UsersController:createUserPage', [
    Middlewares::requirePermissionsPage(['users.write']),
]);
$router->get('/admin/users/{id_user}', 'UsersController:singleUserPage', [
    Middlewares::requirePermissionsPage(['users.read']),
]);
$router->post('/admin/users', 'UsersController:createUser', [
    Middlewares::requirePermissionsPage(['users.write']),
]);
$router->put('/admin/users/{id_user}', 'UsersController:updateUser', [
    Middlewares::requirePermissionsPage(['users.write']),
]);
$router->delete('/admin/users/{id_user}', 'UsersController:deleteUser', [
    Middlewares::requirePermissionsPage(['users.write']),
]);
// partenaires
$router->get('/admin/partenaires', 'PartenairesController:allPartenairesPage', [
    Middlewares::requirePermissionsPage(['partenaires.read']),
]);
$router->get('/admin/partenaires/new', 'PartenairesController:createPartenairePage', [
    Middlewares::requirePermissionsPage(['partenaires.write']),
]);
$router->get('/admin/partenaires/{id_partenaire}', 'PartenairesController:singlePartenairePage', [
    Middlewares::requirePermissionsPage(['partenaires.read']),
]);
$router->post('/admin/partenaires', 'PartenairesController:createPartenaire', [
    Middlewares::requirePermissionsPage(['partenaires.write']),
]);
$router->put('/admin/partenaires/{id_partenaire}', 'PartenairesController:updatePartenaire', [
    Middlewares::requirePermissionsPage(['partenaires.write']),
]);
$router->delete('/admin/partenaires/{id_partenaire}', 'PartenairesController:deletePartenaire', [
    Middlewares::requirePermissionsPage(['partenaires.write']),
]);


// equipes
$router->get('/admin/equipes', 'EquipesController:allEquipesPage', [
    Middlewares::requirePermissionsPage(['equipes.read']),
]);
$router->get('/admin/equipes/new', 'EquipesController:createEquipePage', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->post('/admin/equipes', 'EquipesController:createEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->get('/admin/equipes/{id_equipe}', 'EquipesController:singleEquipePage', [
    Middlewares::requirePermissionsPage(['equipes.read']),
]);
$router->put('/admin/equipes/{id_equipe}', 'EquipesController:updateEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->delete('/admin/equipes/{id_equipe}', 'EquipesController:deleteEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->post('/admin/equipes/{id_equipe}/add-user', 'EquipesController:addUserToEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->post('/admin/equipes/{id_equipe}/remove-user', 'EquipesController:removeUserFromEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);
$router->post('/admin/equipes/{id_equipe}/set-chef', 'EquipesController:setChefEquipe', [
    Middlewares::requirePermissionsPage(['equipes.write']),
]);


// roles
$router->get('/admin/roles', 'RolesController:allRolesPage', [
    Middlewares::requirePermissionsPage(['roles.read']),
]);
$router->get('/admin/roles/new', 'RolesController:createRolePage', [
    Middlewares::requirePermissionsPage(['roles.write']),
]);
$router->get('/admin/roles/{nom_role}', 'RolesController:singleRolePage', [
    Middlewares::requirePermissionsPage(['roles.read']),
]);
$router->post('/admin/roles', 'RolesController:createRole', [
    Middlewares::requirePermissionsPage(['roles.write']),
]);
$router->put('/admin/roles/{nom_role}/permissions', 'RolesController:updateRolePermissions', [
    Middlewares::requirePermissionsPage(['roles.write']),
]);
$router->delete('/admin/roles/{nom_role}', 'RolesController:deleteRole', [
    Middlewares::requirePermissionsPage(['roles.write']),
]);


// projets
$router->get('/admin/projets', 'ProjetsController:allProjetsPage', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->get('/admin/projets/new', 'ProjetsController:createProjetPage', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets', 'ProjetsController:create', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->get('/admin/projets/stats', 'ProjetsController:stats', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->get('/admin/projets/{id_projet}', 'ProjetsController:singleProjetPage', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->put('/admin/projets/{id_projet}', 'ProjetsController:edit', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->put('/admin/projets/{id_projet}/cloturer', 'ProjetsController:cloturer', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->put('/admin/projets/{id_projet}/reouvrir', 'ProjetsController:reouvrir', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/add-user', 'ProjetsController:addUser', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/remove-user', 'ProjetsController:removeUser', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/add-partenaire', 'ProjetsController:addPartenaire', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/remove-partenaire', 'ProjetsController:removePartenaire', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/set-responsable', 'ProjetsController:setResponsable', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->get('/admin/projets/stats', 'ProjetsController:stats', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->get('/admin/projets/report', 'ProjetsController:report', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->delete('/admin/projets/{id_projet}', 'ProjetsController:delete', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);

// ----------------- equipements -----------------
$router->get('/admin/equipements', 'EquipementsController:allEquipementsPage', [
    Middlewares::requirePermissionsPage(['equipements.read']),
]);
$router->get('/admin/equipements/new', 'EquipementsController:createEquipementPage', [
    Middlewares::requirePermissionsPage(['equipements.write']),
]);
$router->post('/admin/equipements', 'EquipementsController:create', [
    Middlewares::requirePermissionsPage(['equipements.write']),
]);
$router->get('/admin/equipements/{id_equipement}', 'EquipementsController:singleEquipementPage', [
    Middlewares::requirePermissionsPage(['equipements.read']),
]);
$router->put('/admin/equipements/{id_equipement}', 'EquipementsController:edit', [
    Middlewares::requirePermissionsPage(['equipements.write']),
]);
$router->post('/admin/equipements/{id_equipement}/statut', 'EquipementsController:updateStatut', [
    Middlewares::requirePermissionsPage(['equipements.write']),
]);
$router->delete('/admin/equipements/{id_equipement}', 'EquipementsController:delete', [
    Middlewares::requirePermissionsPage(['equipements.write']),
]);

// ----------------- reservations -----------------
$router->get('/admin/reservations', 'ReservationsController:index', [
    Middlewares::requirePermissionsPage(['equipements.read']),
]);
$router->post('/admin/equipements/{id_equipement}/reserver', 'EquipementsController:reserver', [
    Middlewares::requirePermissionsPage(['equipements.read']),
]);
$router->put('/admin/reservations/{id_reservation}', 'EquipementsController:editReservation', [
]);
$router->delete('/admin/reservations/{id_reservation}', 'EquipementsController:deleteReservation', [
]);

// --------------- publications ---------------
$router->get('/admin/publications', 'PublicationsController:allPublicationsPage', [
    Middlewares::requireAuthPage(),
]);
$router->get('/admin/publications/pending', 'PublicationsController:pendingPublicationsPage', [
    Middlewares::requirePermissionsPage(['publications.write']),
]);
$router->get('/admin/publications/new', 'PublicationsController:newPublicationPage', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/new', 'PublicationsController:createPublication', [
    Middlewares::requireAuthPage(),
]);
$router->get('/admin/publications/{id_publication}', 'PublicationsController:singlePublicationPage', [
    Middlewares::requireAuthPage(),
]);
$router->get('/admin/publications/{id_publication}/edit', 'PublicationsController:editPublicationPage', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/{id_publication}/edit', 'PublicationsController:updatePublication', [
    Middlewares::requireAuthPage(),
]);
$router->delete('/admin/publications/{id_publication}', 'PublicationsController:deletePublication', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/{id_publication}/accept', 'PublicationsController:acceptPublication', [
    Middlewares::requirePermissionsPage(['publications.write']),
]);
$router->post('/admin/publications/{id_publication}/refuse', 'PublicationsController:refusePublication', [
    Middlewares::requirePermissionsPage(['publications.write']),
]);
$router->post('/admin/publications/{id_publication}/add-auteur', 'PublicationsController:addAuteur', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/{id_publication}/add-projet', 'PublicationsController:addProjet', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/{id_publication}/remove-auteur', 'PublicationsController:removeAuteur', [
    Middlewares::requireAuthPage(),
]);
$router->post('/admin/publications/{id_publication}/remove-projet', 'PublicationsController:removeProjet', [
    Middlewares::requireAuthPage(),
]);

// --------------- evenements admin ---------------
$router->get('/admin/evenements', 'EventsController:allEventsPage', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->get('/admin/evenements/new', 'EventsController:createEventPage', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->post('/admin/evenements', 'EventsController:create', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->get('/admin/evenements/{id_evenement}', 'EventsController:singleEventPage', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->get('/admin/evenements/{id_evenement}/edit', 'EventsController:editEventPage', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->post('/admin/evenements/{id_evenement}/edit', 'EventsController:edit', [
    Middlewares::requirePermissionsPage(['events.write']),
]);
$router->post('/admin/evenements/{id_evenement}/delete', 'EventsController:delete', [
    Middlewares::requirePermissionsPage(['events.write']),
]);

// --------------- actualites ---------------
$router->get('/admin/actualites', 'ActualitesController:allActualitesPage', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->get('/admin/actualites/new', 'ActualitesController:createActualitePage', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->post('/admin/actualites', 'ActualitesController:create', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->get('/admin/actualites/{id_actualite}', 'ActualitesController:singleActualitePage', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->get('/admin/actualites/{id_actualite}/edit', 'ActualitesController:editActualitePage', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->post('/admin/actualites/{id_actualite}/edit', 'ActualitesController:edit', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);
$router->post('/admin/actualites/{id_actualite}/delete', 'ActualitesController:delete', [
    Middlewares::requirePermissionsPage(['actualites.write']),
]);

// paramètres admin
$router->get('/admin/parametres', 'ParametresController:index', [
    Middlewares::requirePermissionsPage(['parametres.write']),
]);
$router->post('/admin/parametres', 'ParametresController:save', [
    Middlewares::requirePermissionsPage(['parametres.write']),
]);

// public equipes page
$router->get('/contact', 'PublicContactController:index');
$router->get('/admin/contacts', 'AdminContactsController:index', [
    Middlewares::requirePermissionsPage(['contacts.read']),
]);
$router->post('/contact', 'PublicContactController:envoyer', [
]);
$router->get('/evenements', 'PublicEvenementsController:index');
$router->get('/evenements/{id_evenement}', 'PublicEvenementsController:singleEvenement');
$router->post('/evenements/{id_evenement}/inscrire', 'PublicEvenementsController:inscrire');
$router->get('/actualites', 'PublicActualitesController:index');
$router->get('/projets', 'PublicProjetsController:index');
$router->get('/projets/{id_projet}', 'PublicProjetsController:singleProjet');
$router->get('/equipes', 'PublicEquipesController:index');
$router->get('/equipes/{id_equipe}', 'PublicEquipesController:singleEquipe');
$router->get('/users/{id_user}', 'PublicEquipesController:singleUser');
$router->get('/publications', 'PublicPublicationsController:index');
$router->get('/publications/{id_publication}', 'PublicPublicationsController:singlePublication');


// --------------- end ---------------
$router->dispatch();
