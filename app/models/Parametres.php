<?php
require_once 'BaseModel.php';

class Parametres extends BaseModel {
    protected static $table = 'parametres';
    protected static $columns = [
        'id_parametre', 'cle', 'valeur', 'description'
    ];
    protected static $fillable = [
        'cle', 'valeur', 'description'
    ];
    protected static $hidden = [];
    public static $pk = 'id_parametre';

    public $id_parametre;
    public $cle;
    public $valeur;
    public $description;
}
