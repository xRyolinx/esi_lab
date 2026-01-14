<?php
require_once 'BaseModel.php';

class PublicationAuteur extends BaseModel
{
    protected static $table = 'publication_auteur';
    protected static $columns = [
        'id',
        'id_user',
        'id_publication'
    ];
    protected static $relations = [
        'user' => [
            'related_model' => 'Users',
            'type' => 'many-to-one',
            'self_key' => 'id_user',
            'related_key' => 'id_user',
        ],
        'publication' => [
            'related_model' => 'Publications',
            'type' => 'many-to-one',
            'self_key' => 'id_publication',
            'related_key' => 'id_publication',
        ],
    ];
    protected static $fillable = [
        'id_user',
        'id_publication'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_user;
    public $id_publication;

    public static function countByUser()
    {
        $db = static::db();

        $stmt = $db->query("SELECT id_user, COUNT(*) AS nb_publications FROM " . static::$table . " GROUP BY id_user");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public static function countByEquipe()
    {
        $db = static::db();
        $stmt = $db->query("SELECT u.id_equipe, COUNT(DISTINCT pa.id_publication) AS nb_publications
            FROM publication_auteur pa
            JOIN users u ON pa.id_user = u.id_user
            GROUP BY u.id_equipe"
        );
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}