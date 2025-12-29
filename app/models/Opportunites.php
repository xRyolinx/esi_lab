<?php
require_once 'BaseModel.php';

class Opportunites extends BaseModel {
    protected static $table = 'opportunites';
    protected static $columns = [
        'id_opportunite', 'titre', 'type', 'description', 'date_limite', 'contact', 'statut'
    ];
    protected static $fillable = [
        'titre', 'type', 'description', 'date_limite', 'contact', 'statut'
    ];
    protected static $hidden = [];
    public static $pk = 'id_opportunite';

    public $id_opportunite;
    public $titre;
    public $type;
    public $description;
    public $date_limite;
    public $contact;
    public $statut;
}
