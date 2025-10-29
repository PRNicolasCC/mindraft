<?php
declare(strict_types=1);

class View{

    function __construct(){
        //echo "<p>Vista base</p>";
    }

    public function render($nombre): void{
        require 'public/views/' . $nombre . '.php';
    }
}

?>