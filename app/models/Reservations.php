<?php
require_once 'BaseModel.php';

class Reservations extends BaseModel {
    protected static $table = 'reservations';
    protected static $columns = [
        'id_reservation', 'date_debut', 'date_fin', 'statut', 'date_reservation', 'id_user', 'id_equipement'
    ];
    protected static $fillable = [
        'date_debut', 'date_fin', 'statut', 'date_reservation', 'id_user', 'id_equipement'
    ];
    protected static $hidden = [];
    public static $pk = 'id_reservation';

    public $id_reservation;
    public $date_debut;
    public $date_fin;
    public $statut;
    public $date_reservation;
    public $id_user;
    public $id_equipement;
}
