<?php
require_once 'BaseModel.php';

class Actualites extends BaseModel {
    protected static $table = 'actualites';
    protected static $columns = [
        'id_actualite', 'titre', 'description', 'type', 'image', 'lien_detail', 'date_publication', 'affichage_diaporama'
    ];
    protected static $fillable = [
        'titre', 'description', 'type', 'image', 'lien_detail', 'date_publication', 'affichage_diaporama'
    ];
    protected static $hidden = [];
    public static $pk = 'id_actualite';

    public $id_actualite;
    public $titre;
    public $description;
    public $type;
    public $image;
    public $lien_detail;
    public $date_publication;
    public $affichage_diaporama;
}
