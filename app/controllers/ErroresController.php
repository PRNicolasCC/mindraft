<?php
declare(strict_types=1);

class ErroresController extends Controller{

    function __construct(){
        parent::__construct();
        $this->view->mensaje = "Error 404: La página solicitada no existe.";
        $this->view->render('errores/index');
    }
}

?>