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
    protected static $specific_relations = ['nb_pubs', 'ressources', 'publications'];

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
                $ressources = $data[$equipe['id_equipe']] ?? [];
                
                // Garder une seule occurrence par id_publication
                $unique_arr = [];
                foreach ($ressources as $ressource) {
                    $unique_arr[$ressource['id_equipement']] = $ressource;
                }
                
                // Réindexer le tableau
                $equipe['ressources'] = array_values($unique_arr);
            }
        }

        if (in_array('publications', $include)) {
            $model = PublicationAuteur::leftJoin(
                modelClass: 'Publications',
                primary: 'id_publication',
                foreign: 'id_publication'
            )->leftJoin(
                modelClass: 'Users',
                primary: 'publication_auteur.id_user',
                foreign: 'id_user'
            )->leftJoin(
                    modelClass: 'Equipes',
                    primary: 'users.id_equipe',
                    foreign: 'id_equipe'
                );

            $data = $model->getAllIndexedBy(column: 'id_equipe');

            // associer a chaque equipe ses publications
            foreach ($equipes as &$equipe) {
                $pubs = $data[$equipe['id_equipe']] ?? [];
                
                // Garder une seule occurrence par id_publication
                $unique_arr = [];
                foreach ($pubs as $pub) {
                    $unique_arr[$pub['id_publication']] = $pub;
                }
                
                // Réindexer le tableau
                $equipe['publications'] = array_values($unique_arr);
            }
        }

        return $equipes;
    }

}
