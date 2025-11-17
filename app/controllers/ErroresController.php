<?php
declare(strict_types=1);

class ErroresController extends Controller{

    function __construct(string $error = 'Error 404: La página solicitada no existe.'){
        parent::__construct();
        $this->view->message = [
            'description' => $error,
            'type' => 'error'
        ];

        $this->view->render('errores/index');
    }
}

?>