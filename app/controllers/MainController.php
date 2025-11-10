<?php
declare(strict_types=1);

class MainController extends Controller{

    function __construct(){
        parent::__construct('main');
    }

    function render(): void{
        $view = 'user/index';
        if (SessionManager::isAuthenticated()) {
            $view = 'main/index';
        }
        $this->view->render($view);
    }
}

?>