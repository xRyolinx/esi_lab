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
$router->get('/admin/projets/{id_projet}', 'ProjetsController:singleProjetPage', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->put('/admin/projets/{id_projet}', 'ProjetsController:edit', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);
$router->post('/admin/projets/{id_projet}/cloturer', 'ProjetsController:cloturer', [
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
$router->get('/admin/projets/stats', 'ProjetsController:stats', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->get('/admin/projets/report', 'ProjetsController:report', [
    Middlewares::requirePermissionsPage(['projets.read']),
]);
$router->delete('/admin/projets/{id_projet}', 'ProjetsController:delete', [
    Middlewares::requirePermissionsPage(['projets.write']),
]);

// --------------- end ---------------
$router->dispatch();
