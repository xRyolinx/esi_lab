<?php
require_once 'BaseModel.php';

class ProjetUser extends BaseModel {
        protected static $relations = [
            // Lien projet-user
            'user' => [
                'related_model' => 'Users',
                'type' => 'many-to-one',
                'self_key' => 'id_user',
                'related_key' => 'id_user',
            ],
            'projet' => [
                'related_model' => 'Projets',
                'type' => 'many-to-one',
                'self_key' => 'id_projet',
                'related_key' => 'id_projet',
            ],
        ];
    protected static $table = 'projet_user';
    protected static $columns = [
        'id', 'id_projet', 'id_user'
    ];
    protected static $fillable = [
        'id_projet', 'id_user'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_projet;
    public $id_user;
}
