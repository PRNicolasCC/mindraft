<?php
declare(strict_types=1);

class MainController extends Controller{

    function __construct(){
        parent::__construct();
    }

    function render(): void{
        $view = 'auth/index';
        if (SessionManager::isAuthenticated()) {
            $view = 'main/index';
        }
        $this->view->render($view);
    }
}

?>