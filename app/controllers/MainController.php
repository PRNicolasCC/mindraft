<?php
declare(strict_types=1);

class MainController extends Controller{
    function __construct(){
        parent::__construct();
    }

    function render(): void{
        $this->isAuth();
        $this->view->render('main/index');
    }
}

?>