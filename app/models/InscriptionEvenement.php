<?php
require_once 'BaseModel.php';

class InscriptionEvenement extends BaseModel {
    protected static $table = 'inscription_evenement';
    protected static $columns = [
        'id', 'id_evenement', 'id_user', 'date_inscription'
    ];
    protected static $fillable = [
        'id_evenement', 'id_user', 'date_inscription'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_evenement;
    public $id_user;
    public $date_inscription;
}
