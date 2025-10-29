<?php
declare(strict_types=1);

class Model{
    protected $db;
    function __construct(){
        $this->db = new DBConnection();
    }
}

?>