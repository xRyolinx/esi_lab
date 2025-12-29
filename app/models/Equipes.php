<?php
require_once 'BaseModel.php';
require_once 'Reservations.php';

class Equipes extends BaseModel
{
    protected static $table = 'equipes';
    protected static $columns = [
        'id_equipe',
        'nom_equipe',
        'description',
        'date_creation',
        'id_chef'
    ];
    protected static $relations = [
        // Equipe a des membres (users)
        'membres' => [
            'related_model' => 'Users',
            'type' => 'one-to-many',
            'self_key' => 'id_equipe',
            'related_key' => 'id_equipe',
        ],
        // Equipe a des projets (many-to-many via projet_user)
        'projets' => [
            'related_model' => 'Projets',
            'type' => 'many-to-many',
            'table' => 'projet_user',
            'self_key' => 'id_equipe', // not direct, but via users in projet_user
            'related_key' => 'id_projet',
        ],
        // Equipe a des equipements (one-to-many if you add id_equipe in equipements)
        // 'equipements' => [...]
    ];
    protected static $specific_relations = ['nb_pubs', 'ressources'];

    protected static $fillable = [
        'nom_equipe',
        'description',
        'date_creation',
        'id_chef'
    ];
    protected static $hidden = [];
    public static $pk = 'id_equipe';

    public $id_equipe;
    public $nom_equipe;
    public $description;
    public $date_creation;
    public $id_chef;

    public static function getAll($model = null, array $select = [], array $conditions = [], array $include = [])
    {
        $equipes = parent::getAll(model: $model, select: $select, conditions: $conditions, include: $include);

        // specific relations
        if (in_array('nb_pubs', $include)) {
            $pubs = PublicationAuteur::countByEquipe();
            foreach ($equipes as &$equipe) {
                $equipe['nb_pubs'] = $pubs[$equipe['id_equipe']] ?? 0;
            }
        }

        if (in_array('ressources', $include)) {
            $model = Reservations::leftJoin(
                modelClass: 'Users',
                primary: 'id_user',
                foreign: 'id_user'
            )->leftJoin(
                modelClass: 'Equipements',
                primary: 'reservations.id_equipement',
                foreign: 'id_equipement'
            );
            
            $data = $model->getAllIndexedBy(column: 'id_equipe');

            
            foreach ($equipes as &$equipe) {
                $equipe['ressources'] = $data[$equipe['id_equipe']] ?? [];
            }
        }

        return $equipes;
    }

}
