<?php
require_once 'BaseModel.php';

class Publications extends BaseModel {
        protected static $relations = [
            // Publication a plusieurs auteurs (many-to-many via publication_auteur)
            'auteurs' => [
                'related_model' => 'Users',
                'type' => 'many-to-many',
                'table' => 'publication_auteur',
                'self_key' => 'id_publication',
                'related_key' => 'id_user',
            ],
            // Publication liée à des projets (many-to-many via projet_publication)
            'projets' => [
                'related_model' => 'Projets',
                'type' => 'many-to-many',
                'table' => 'projet_publication',
                'self_key' => 'id_publication',
                'related_key' => 'id_projet',
            ],
        ];
    protected static $table = 'publications';
    protected static $columns = [
        'id_publication', 'titre', 'resume', 'type', 'doi', 'lien_telechargement', 'annee', 'domaine', 'date_publication', 'statut'
    ];
    protected static $fillable = [
        'titre', 'resume', 'type', 'doi', 'lien_telechargement', 'annee', 'domaine', 'date_publication', 'statut'
    ];
    protected static $hidden = [];
    public static $pk = 'id_publication';

    public $id_publication;
    public $titre;
    public $resume;
    public $type;
    public $doi;
    public $lien_telechargement;
    public $annee;
    public $domaine;
    public $date_publication;
    public $statut;
}
