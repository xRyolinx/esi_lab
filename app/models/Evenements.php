<?php
require_once 'BaseModel.php';

class Evenements extends BaseModel {
        protected static $relations = [
            // Evenement a plusieurs inscrits (many-to-many via inscription_evenement)
            'users' => [
                'related_model' => 'Users',
                'type' => 'many-to-many',
                'table' => 'inscription_evenement',
                'self_key' => 'id_evenement',
                'related_key' => 'id_user',
            ],
        ];
    protected static $table = 'evenements';
    protected static $columns = [
        'id_evenement', 'titre', 'description', 'type', 'lieu', 'date_debut', 'date_fin', 'nb_max_participants', 'image'
    ];
    protected static $fillable = [
        'titre', 'description', 'type', 'lieu', 'date_debut', 'date_fin', 'nb_max_participants', 'image'
    ];
    protected static $hidden = [];
    public static $pk = 'id_evenement';

    public $id_evenement;
    public $titre;
    public $description;
    public $type;
    public $lieu;
    public $date_debut;
    public $date_fin;
    public $nb_max_participants;
    public $image;
}
