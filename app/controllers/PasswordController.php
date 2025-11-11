<?php
declare(strict_types=1);

class PasswordController extends Controller {
    /* private $userModel;
    private $emailService; */

    public function __construct() {
        parent::__construct();
        /* $this->userModel = new User();
        $this->emailService = new EmailService(); */
    }

    public function render(): void{
        $view = 'password_recuperation/index';
        $this->view->render($view);
    }
}

?>