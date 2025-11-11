<?php
declare(strict_types=1);

abstract class Model{
    protected $db;
    function __construct(){
        $this->db = new DBConnection();
    }
}

?>