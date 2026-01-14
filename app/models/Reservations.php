<?php
require_once 'BaseModel.php';

class Reservations extends BaseModel {
    protected static $table = 'reservations';
    protected static $columns = [
        'id_reservation', 'date_debut', 'date_fin', 'date_reservation', 'id_user', 'id_equipement'
    ];
    protected static $relations = [
        // chaque reservation concerne un seul équipement
        'equipement' => [
            'related_model' => 'Equipements',
            'type' => 'many-to-one',
            'self_key' => 'id_equipement',
            'related_key' => 'id_equipement',
        ],
        // chaque reservation concerne un seul user
        'user' => [
            'related_model' => 'Users',
            'type' => 'many-to-one',
            'self_key' => 'id_user',
            'related_key' => 'id_user',
        ],
    ];
    protected static $fillable = [
        'date_debut', 'date_fin', 'date_reservation', 'id_user', 'id_equipement'
    ];
    protected static $hidden = [];
    public static $pk = 'id_reservation';

    public $id_reservation;
    public $date_debut;
    public $date_fin;
    public $date_reservation;
    public $id_user;
    public $id_equipement;
}
