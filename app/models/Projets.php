<?php
require_once 'BaseModel.php';

class Projets extends BaseModel
{
    protected static $relations = [
        // Projet a plusieurs utilisateurs (many-to-many via projet_user)
        'users' => [
            'related_model' => 'Users',
            'type' => 'many-to-many',
            'table' => 'projet_user',
            'self_key' => 'id_projet',
            'related_key' => 'id_user',
        ],
        // Projet a plusieurs partenaires (many-to-many via projet_partenaire)
        'partenaires' => [
            'related_model' => 'Partenaires',
            'type' => 'many-to-many',
            'table' => 'projet_partenaire',
            'self_key' => 'id_projet',
            'related_key' => 'id_partenaire',
        ],
        // Projet a plusieurs publications (many-to-many via projet_publication)
        'publications' => [
            'related_model' => 'Publications',
            'type' => 'many-to-many',
            'table' => 'projet_publication',
            'self_key' => 'id_projet',
            'related_key' => 'id_publication',
        ],
    ];
    protected static $table = 'projets';
    protected static $columns = [
        'id_projet',
        'titre',
        'description',
        'thematique',
        'type_financement',
        'statut',
        'date_debut',
        'date_fin'
    ];
    protected static $fillable = [
        'titre',
        'description',
        'thematique',
        'type_financement',
        'statut',
        'date_debut',
        'date_fin'
    ];
    protected static $hidden = [];
    public static $pk = 'id_projet';

    public $id_projet;
    public $titre;
    public $description;
    public $thematique;
    public $type_financement;
    public $statut;
    public $date_debut;
    public $date_fin;


    public static function countByThematique()
    {
        $db = static::db();
        $sql = 'SELECT thematique, COUNT(*) as nb FROM projets GROUP BY thematique';
        return $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public static function countByAnnee()
    {
        $db = static::db();
        $sql = 'SELECT YEAR(date_debut) as annee, COUNT(*) as nb FROM projets GROUP BY annee';
        return $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public static function countByEncadrant()
    {
        $db = static::db();
        $sql = 'SELECT u.nom, u.prenom, COUNT(*) as nb
                FROM projet_user pu
                JOIN users u ON pu.id_user = u.id_user
                WHERE pu.is_responsable = 1
                GROUP BY pu.id_user';
        $res = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($res as $row) {
            $out[$row['prenom'] . ' ' . $row['nom']] = $row['nb'];
        }
        return $out;
    }
}
