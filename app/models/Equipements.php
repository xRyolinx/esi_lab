<?php
require_once 'BaseModel.php';

class Equipements extends BaseModel {
        protected static $relations = [
            // Equipement a plusieurs réservations (one-to-many)
            'reservations' => [
                'related_model' => 'Reservations',
                'type' => 'one-to-many',
                'self_key' => 'id_equipement',
                'related_key' => 'id_equipement',
            ],
            // Equipement réservé par plusieurs users (many-to-many via reservations)
            'users' => [
                'related_model' => 'Users',
                'type' => 'many-to-many',
                'table' => 'reservations',
                'self_key' => 'id_equipement',
                'related_key' => 'id_user',
            ],
        ];
    protected static $table = 'equipements';
    protected static $columns = [
        'id_equipement', 'nom', 'type', 'description', 'etat', 'localisation'
    ];
    protected static $fillable = [
        'nom', 'type', 'description', 'etat', 'localisation'
    ];
    protected static $hidden = [];
    public static $pk = 'id_equipement';

    public $id_equipement;
    public $nom;
    public $type;
    public $description;
    public $etat;
    public $localisation;

    public static function getAll($model = null, array $select = [], array $conditions = [], array $include = []) {
        $equipements = parent::getAll(model: $model, select: $select, conditions: $conditions, include: $include);

        if ($include['user'] ?? false) {
            $users = Users::getAllIndexedBy(column: 'id_equipement');
            foreach ($equipements as &$equipement) {
                $equipement['user'] = $users[$equipement['id_equipement']] ?? null;
            }
        }
        return $equipements;
    }

    public static function getByUser() {
    }
}