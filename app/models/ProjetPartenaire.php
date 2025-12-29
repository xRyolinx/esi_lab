<?php
require_once 'BaseModel.php';

class ProjetPartenaire extends BaseModel {
    protected static $table = 'projet_partenaire';
    protected static $columns = [
        'id', 'id_projet', 'id_partenaire'
    ];
    protected static $fillable = [
        'id_projet', 'id_partenaire'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_projet;
    public $id_partenaire;
}
