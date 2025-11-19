<?php
declare(strict_types=1);

require_once 'app/controllers/UserController.php';
require_once 'app/services/EmailService.php';

class PasswordController extends UserController {
    function __construct() {
        parent::__construct('password');
        $this->setGetActions([
            'form',
            'reset',
        ]);
        $this->setTokenMethods([
            'reset',
        ]);
    }

    function render(): void {
        $this->isNotAuth();
        $this->view->render('password/index');
    }

    function form(): void {
        $this->isNotAuth();
        if (!SessionManager::has('redirectInputs') || !isset(SessionManager::get('redirectInputs')['email']) || !isset(SessionManager::get('redirectInputs')['token'])) {
            $this->redirect('/');
        }
        $this->view->render('password/form_reset');
    }

    function reset(array $data): void {
        $this->validateUserData([
            'email' => $data[1],
        ], false);

        if($this->model->isUsedToken($data[1], $data[0], 'P')) {
            $this->warningRedirect('El token ya ha sido utilizado. Por favor, inicia sesión o solicita un nuevo correo de recuperación.');
        }

        $this->redirect('/password/form', '', '', ['email' => $data[1], 'token' => $data[0]]);
    }

    function sendEmail(array $data): void {
        $this->validateUserData([
            'email' => $data['email'],
        ], false);

        $datosUsuario = $this->model->obtenerPorEmail($data['email']);
        if (empty($datosUsuario)) $this->warningRedirect('El correo electrónico no se encuentra registrado. Puedes crear una nueva cuenta con este correo electrónico');

        $this->validateUserActive($data);

        $token = $this->model->almacenarToken($datosUsuario['id'], 'P', 1);
        EmailService::sendEmailRecuperacion(
            $datosUsuario['email'], 
            $token
        );

        $this->successRedirect(
            'Se ha enviado un correo electrónico con un enlace a ' . $datosUsuario['email'] . ' para restablecer la contraseña',                 
            [],
            '/'
        );
    }

    function change(array $data): void {
        $this->validateUserData([
            'email' => $data['email'],
        ], false);

        $this->validatePassword($data);

        $passHash = $this->hashPassword($data['password']);
        $isActive = $this->model->restablecerPassword($data['email'], $data['token'], $passHash);

        if (gettype($isActive) === 'string') {
            $this->warningRedirect($isActive, ['email' => $data[1]], '/');
        } else if ($isActive) {
            $this->successRedirect(
                'Se ha cambiado la contraseña correctamente, ya puedes iniciar sesión',
                ['email' => $data[1]],
                '/'
            );
        } else {
            $this->warningRedirect('Error de validación. No se ha podido restablecer la contraseña.');
        }
    }
}