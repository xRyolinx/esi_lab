<?php
require_once 'BaseModel.php';

class Permissions extends BaseModel {
    protected static $table = 'permissions';
    protected static $columns = [
        'nom_permission', 'description'
    ];
    protected static $fillable = [
        'nom_permission', 'description'
    ];
    protected static $hidden = [];
    public static $pk = 'nom_permission';

    public $nom_permission;
    public $description;
}
