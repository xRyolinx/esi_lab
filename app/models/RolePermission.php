<?php
require_once 'BaseModel.php';

class RolePermission extends BaseModel {
    protected static $table = 'role_permission';
    protected static $columns = [
        'id', 'nom_role', 'nom_permission'
    ];
    protected static $fillable = [
        'nom_role', 'nom_permission'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $nom_role;
    public $nom_permission;
}
