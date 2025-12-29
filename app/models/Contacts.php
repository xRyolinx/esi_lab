<?php
require_once 'BaseModel.php';

class Contacts extends BaseModel {
    protected static $table = 'contacts';
    protected static $columns = [
        'id_contact', 'nom', 'email', 'sujet', 'message', 'date_envoi', 'statut'
    ];
    protected static $fillable = [
        'nom', 'email', 'sujet', 'message', 'date_envoi', 'statut'
    ];
    protected static $hidden = [];
    public static $pk = 'id_contact';

    public $id_contact;
    public $nom;
    public $email;
    public $sujet;
    public $message;
    public $date_envoi;
    public $statut;
}
