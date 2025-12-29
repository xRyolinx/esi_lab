<?php
require_once 'BaseModel.php';

class Partenaires extends BaseModel {
    protected static $table = 'partenaires';
    protected static $columns = [
        'id_partenaire', 'nom', 'type', 'logo', 'site_web', 'description'
    ];
    protected static $fillable = [
        'nom', 'type', 'logo', 'site_web', 'description'
    ];
    protected static $hidden = [];
    public static $pk = 'id_partenaire';

    public $id_partenaire;
    public $nom;
    public $type;
    public $logo;
    public $site_web;
    public $description;
}
