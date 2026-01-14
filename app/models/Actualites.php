<?php
require_once 'BaseModel.php';

class Actualites extends BaseModel {
    protected static $table = 'actualites';
    protected static $columns = [
        'id_actualite', 'titre', 'description', 'type', 'date_publication'
    ];
    protected static $fillable = [
        'titre', 'description', 'type'
    ];
    protected static $hidden = [];
    public static $pk = 'id_actualite';

    public $id_actualite;
    public $titre;
    public $description;
    public $type;
    public $date_publication;
}
