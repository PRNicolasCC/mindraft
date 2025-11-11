<?php
declare(strict_types=1);

class MainController extends Controller{
    private const REDIRECT1 = 'user/index';
    private const REDIRECT2 = 'main/index';

    function __construct(){
        parent::__construct('main');
        $this->view->setRedirect(self::REDIRECT1);
    }

    function render(): void{
        if (SessionManager::isAuthenticated()) {
            $this->view->setRedirect(self::REDIRECT2);
        }
        $this->view->render();
    }
}

?>