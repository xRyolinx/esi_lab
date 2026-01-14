<?php
require_once 'BaseModel.php';

class Equipements extends BaseModel {
        protected static $relations = [
            // Equipement a plusieurs réservations (one-to-many)
            'reservations' => [
                'related_model' => 'Reservations',
                'type' => 'one-to-many',
                'self_key' => 'id_equipement',
                'related_key' => 'id_equipement',
            ],
            // Equipement réservé par plusieurs users (many-to-many via reservations)
            'users' => [
                'related_model' => 'Users',
                'type' => 'many-to-many',
                'table' => 'reservations',
                'self_key' => 'id_equipement',
                'related_key' => 'id_user',
            ],
        ];
    protected static $table = 'equipements';
    protected static $columns = [
        'id_equipement', 'nom', 'type', 'description', 'statut', 'localisation'
    ];
    protected static $fillable = [
        'nom', 'type', 'description', 'statut', 'localisation'
    ];
    protected static $hidden = [];
    public static $pk = 'id_equipement';

    public $id_equipement;
    public $nom;
    public $type;
    public $description;
    public $etat;
    public $localisation;
}