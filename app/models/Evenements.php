<?php
require_once 'BaseModel.php';

class Evenements extends BaseModel
{
    protected static $relations = [
        'inscrits' => [
            'related_model' => 'Users',
            'type' => 'many-to-many',
            'table' => 'inscription_evenement',
            'self_key' => 'id_evenement',
            'related_key' => 'id_user',
        ],
    ];
    protected static $table = 'evenements';
    protected static $columns = [
        'id_evenement',
        'titre',
        'description',
        'type',
        'isPublic',
        'lieu',
        'date_debut',
        'date_fin',
        'nb_max_participants'
    ];
    protected static $fillable = [
        'titre',
        'description',
        'type',
        'isPublic',
        'lieu',
        'date_debut',
        'date_fin',
        'nb_max_participants'
    ];
    protected static $hidden = [];
    public static $pk = 'id_evenement';

    public $id_evenement;
    public $titre;
    public $description;
    public $type;
    public $isPublic;
    public $lieu;
    public $date_debut;
    public $date_fin;
    public $nb_max_participants;
}
