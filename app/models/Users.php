<?php
require_once 'BaseModel.php';
require_once 'PublicationAuteur.php';

class Users extends BaseModel
{
    protected static $table = 'users';
    protected static $columns = [
        'id_user',
        'nom',
        'prenom',
        'email',
        'password',
        'username',
        'photo',
        'grade',
        'domaine_recherche',
        'biographie',
        'role',
        'statut',
        'date_creation',
        'id_equipe'
    ];
    protected static $relations = [
        // User appartient à une équipe
        'equipe' => [
            'related_model' => 'Equipes',
            'type' => 'many-to-one',
            'self_key' => 'id_equipe',
            'related_key' => 'id_equipe',
        ],
        // User a des réservations d'équipements (many-to-many via reservations)
        'equipements' => [
            'related_model' => 'Equipements',
            'type' => 'many-to-many',
            'table' => 'reservations',
            'self_key' => 'id_user',
            'related_key' => 'id_equipement',
        ],
        // User a des publications (many-to-many via publication_auteur)
        'publications' => [
            'related_model' => 'Publications',
            'type' => 'many-to-many',
            'table' => 'publication_auteur',
            'self_key' => 'id_user',
            'related_key' => 'id_publication',
        ],
        // User participe à des projets (many-to-many via projet_user)
        'projets' => [
            'related_model' => 'Projets',
            'type' => 'many-to-many',
            'table' => 'projet_user',
            'self_key' => 'id_user',
            'related_key' => 'id_projet',
        ],
        // User a des réservations (one-to-many)
        'reservations' => [
            'related_model' => 'Reservations',
            'type' => 'one-to-many',
            'self_key' => 'id_user',
            'related_key' => 'id_user',
        ],
        // User inscrit à des evenements (many-to-many via inscription_evenement)
        'evenements' => [
            'related_model' => 'Evenements',
            'type' => 'many-to-many',
            'table' => 'inscription_evenement',
            'self_key' => 'id_user',
            'related_key' => 'id_evenement',
        ],
    ];
    protected static $specific_relations = ['nb_pubs'];

    protected static $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'username',
        'photo',
        'grade',
        'domaine_recherche',
        'biographie',
        'role',
        'statut',
        'date_creation',
        'id_equipe'
    ];
    protected static $hidden = [
        'password'
    ];
    public static $pk = 'id_user';

    public $id_user;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $username;
    public $photo;
    public $grade;
    public $domaine_recherche;
    public $biographie;
    public $role;
    public $statut;
    public $date_creation;
    public $id_equipe;

    public static function getAll($model = null, array $select = [], array $conditions = [], array $include = [])
    {
        $users = parent::getAll(model: $model, select: $select, conditions: $conditions, include: $include);

        // specific relations
        if ($include['nb_pubs'] ?? false) {
            $nb_publications = PublicationAuteur::countByUser();
            foreach ($users as &$user) {
                $user['nb_pubs'] = $nb_publications[$user['id_user']] ?? 0;
            }
        }

        return $users;
    }
}
