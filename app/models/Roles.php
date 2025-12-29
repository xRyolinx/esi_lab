<?php
require_once 'BaseModel.php';

class Roles extends BaseModel {
    protected static $table = 'roles';
    protected static $columns = [
        'nom_role', 'description'
    ];
    protected static $fillable = [
        'nom_role', 'description'
    ];
    protected static $hidden = [];
    public static $pk = 'nom_role';

    public $nom_role;
    public $description;
}
