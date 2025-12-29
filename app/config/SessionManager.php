<?php
class SessionManager
{
    // id_user
    // logged_in
    // user
    // permissions
    // flash

    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ---------------- Auth ----------------
    public static function login($user)
    {
        self::start();
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['user'] = $user;
        $_SESSION['logged_in'] = true;

        // Régénérer l'ID de session après login (sécurité)
        session_regenerate_id(true);
    }

    public static function logout()
    {
        self::start();

        // del tout sauf notif
        $flash = $_SESSION['flash'] ?? [];
        $_SESSION = [];
        $_SESSION['flash'] = $flash;
        
        session_regenerate_id(true);
    }

    public static function setPermissions(array $permissions) {
        self::start();
        $_SESSION['permissions'] = $permissions;
    }

    public static function setUser(array $user) {
        self::start();
        $_SESSION['user'] = $user;
    }


    // ---------------- check info ----------------
    public static function isLoggedIn() {
        self::start();

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }

        return true;
    }

    public static function hasPermissions(array $permissions) {
        self::start();

        $sessionPermissions = $_SESSION['permissions'] ?? [];
        foreach ($permissions as $permission) {
            if (!in_array($permission, $sessionPermissions)) {
                return false;
            }
        }
        return true;
    }


    // ---------------- get info ----------------
    public static function getUserId() {
        self::start();
        return $_SESSION['id_user'] ?? null;
    }

    public static function getRole() {
        self::start();
        return $_SESSION['user']['role'] ?? null;
    }

    public static function getPermissions() {
        self::start();
        return $_SESSION['permissions'] ?? [];
    }

    public static function getUserData() {
        self::start();
        return self::isLoggedIn() ? ($_SESSION['user'] ?? null) : null;
    }


    // ---------------- notif ----------------
    public static function setFlashMessage($type, $message) {
        self::start();
        $_SESSION['flash'][$type][] = $message;
    }

    public static function getFlashMessage($type) {
        self::start();
        if (isset($_SESSION['flash'][$type])) {
            $messages = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $messages;
        }
        return [];
    }
}
?>