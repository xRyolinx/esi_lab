<?php
class Database
{
    private static $host = "localhost";
    private static $db_name = "TDW";
    private static $username = "admin";
    private static $password = "admin";
    private static $conn = null;
    public static function db()
    {
        if (static::$conn === null) {
            try {
                static::$conn = new PDO(
                    "mysql:host=" . static::$host . ";dbname=" . static::$db_name . ";charset=utf8mb4",
                    static::$username,
                    static::$password
                );
                static::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return static::$conn;
    }
}
