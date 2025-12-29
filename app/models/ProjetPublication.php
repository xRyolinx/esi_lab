<?php
require_once 'BaseModel.php';

class ProjetPublication extends BaseModel {
        protected static $relations = [
            'projet' => [
                'related_model' => 'Projets',
                'type' => 'many-to-one',
                'self_key' => 'id_projet',
                'related_key' => 'id_projet',
            ],
            'publication' => [
                'related_model' => 'Publications',
                'type' => 'many-to-one',
                'self_key' => 'id_publication',
                'related_key' => 'id_publication',
            ],
        ];
    protected static $table = 'projet_publication';
    protected static $columns = [
        'id', 'id_projet', 'id_publication'
    ];
    protected static $fillable = [
        'id_projet', 'id_publication'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_projet;
    public $id_publication;
}
