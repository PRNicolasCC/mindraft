<?php
declare(strict_types=1);

class MainController extends Controller{
    function __construct(){
        parent::__construct('main');
    }

    function render(): void{
        if (SessionManager::isAuthenticated()) {
            $this->view->render('main/index');
        }
        $this->view->render('user/index');
    }
}

?>