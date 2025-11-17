<?php
declare(strict_types=1);

abstract class Model{
    protected $db;
    protected $dateTime;

    function __construct(){
        $this->db = new DBConnection();
        $this->dateTime = new DateTime('now', new DateTimeZone('America/Bogota')); // Se debe tener la misma zona horaria local para no tener discrepancias en la BD
        //$this->dateTime = new DateTime('now', new DateTimeZone('UTC'));
    }
}

?>