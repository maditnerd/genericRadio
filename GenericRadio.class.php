<?php

/**
* Classe de gestion SQL de la table genericradio liée à la classe GenericRadio 
* @author: valentin carruesco <idleman@idleman.fr>
*/

//La classe GenericRadio hérite de SQLiteEntity qui lui ajoute des méthode de gestion de sa table en bdd (save,delete...)
class GenericRadio extends SQLiteEntity
{
    public $name;
    public $description;
    public $room;
    public $id;
    public $offCommand;
    public $onCommand;
    public $icon;
    public $radiocodeOn;
    public $radiocodeOff;
    public $state; //Pour rajouter des champs il faut ajouter les variables ici...
    protected $TABLE_NAME = 'plugin_genericradio';  //Pensez à mettre le nom de la table sql liée a cette classe
    protected $CLASS_NAME = 'genericradio'; //Nom de la classe courante
    protected $object_fields =
    array( // Ici on définit les noms des champs sql de la table et leurs types
        'name'=>'string',
        'onCommand'=>'string',
        'offCommand'=>'string',
        'description'=>'string',
        'radiocodeOn'=>'int',
        'radiocodeOff'=>'int',
        
        'room'=>'int',
        'icon'=>'string',
        'pulse'=>'int',
        'state'=>'int',
        'id'=>'key'
        );

    function __construct()
    {
        parent::__construct();
    }
}

?>