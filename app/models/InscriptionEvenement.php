<?php
require_once 'BaseModel.php';

class InscriptionEvenement extends BaseModel {
        // Vérifie si un utilisateur est déjà inscrit à un événement
        public static function isUserInscrit($id_evenement, $id_user)
        {
            
        }
    protected static $table = 'inscription_evenement';
    protected static $columns = [
        'id', 'id_evenement', 'id_user', 'date_inscription'
    ];
    protected static $fillable = [
        'id_evenement', 'id_user', 'date_inscription'
    ];
    protected static $hidden = [];
    public static $pk = 'id';

    public $id;
    public $id_evenement;
    public $id_user;
    public $date_inscription;
}
