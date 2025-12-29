<?php
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/RolePermission.php';

class Middlewares
{
    // --------------- handle errors ---------------
    private static function handlePageError($message, $redirectPath = null)
    {
        return function () use ($message, $redirectPath) {
            // err notif
            SessionManager::setFlashMessage('error', $message);

            // actualiser
            if ($redirectPath == null) {
                $redirectPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                header("Location: $redirectPath");
                exit();
            }

            // redirect
            header("Location: $redirectPath?redirect=" . urlencode($_SERVER['REQUEST_URI']));
            exit();
        };
    }

    private static function handleApiError($statusCode, $message)
    {
        return function () use ($statusCode, $message) {
            http_response_code($statusCode);
            echo json_encode(['error' => $message]);
            exit();
        };
    }


    // --------------- refresh session data ---------------
    public static function refreshUser()
    {
        return function () {
            // if not logged in, next()
            if (!SessionManager::isLoggedIn()) {
                return;
            }

            // get user from db and update session
            $id_user = SessionManager::getUserId();
            $user = Users::getUnique(conditions: [['id_user' => ['valeur' => $id_user]]]);
            if ($user) {
                $user_protected = Users::hide($user);
                SessionManager::setUser($user_protected);
            } else {
                // nettoyer la session if user n'existe plus
                SessionManager::logout();
            }
        };
    }

    public static function refreshPermissions()
    {
        return function () {
            // si user n'a pas de role
            $role = SessionManager::getRole();
            if ($role === null || $role === '') {
                SessionManager::setPermissions([]);
                return;
            }

            // get permissions of the user's role
            $perms = RolePermission::getAll(conditions: [['nom_role' => ['valeur' => $role]]]);
            $role_permissions = array_map(fn($rp) => $rp['nom_permission'], $perms);

            // save permissions in session
            SessionManager::setPermissions($role_permissions);
        };
    }


    // -------------- gen purpose function ---------------
    public static function requireAuth(callable $handleError)
    {
        return function () use ($handleError) {
            // refresh user
            self::refreshUser()();

            // check if logged in
            if (!SessionManager::isLoggedIn()) {
                $handleError();
            };

            // refresh permissions
            self::refreshPermissions()();
        };
    }

    public static function requirePermissions(callable $requireAuth, callable $handleError, array $permissions)
    {
        return function () use ($requireAuth, $handleError, $permissions) {
            $requireAuth()();

            if (!SessionManager::hasPermissions($permissions)) {
                $handleError();
            };
        };
    }


    // --------------- specific functions ---------------
    // pages middlewares
    public static function requireAuthPage()
    {
        $handleError = self::handlePageError("Vous n'êtes pas connecté.", '/login');
        return self::requireAuth($handleError);
    }

    public static function requirePermissionsPage($permissions)
    {
        $requireAuth = [self::class, 'requireAuthPage'];
        $handleError = self::handlePageError("Vous n'avez pas les droits nécessaires.", '/admin/dashboard');
        return self::requirePermissions($requireAuth, $handleError, $permissions);
    }


    // api middlewares
    public static function requireAuthApi()
    {
        $handleError = self::handleApiError(401, 'Unauthorized');
        return self::requireAuth($handleError);
    }

    public static function requirePermissionsApi($permissions)
    {
        $requireAuth = [self::class, 'requireAuthApi'];
        $handleError = self::handleApiError(403, 'Forbidden');
        return self::requirePermissions($requireAuth, $handleError, $permissions);
    }
}
