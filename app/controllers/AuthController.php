<?php
declare(strict_types=1);

require_once 'app/controllers/UserController.php';

class AuthController extends UserController {
    function __construct() {
        parent::__construct('auth');
    }

    function render(): void{
        $this->isNotAuth();
        $this->view->render('auth/index');
    }

    function login(array $data): void {
        $this->validateUserData([
            'email' => $data['emailLogin'],
        ], false);

        $email = $data['emailLogin'];
        $password = $data['passwordLogin'];

        $usuario = $this->model->obtenerPorEmail($email);
        if (!empty($usuario) && password_verify($password, $usuario['contraseña'])) {
            if (!$this->model->verificarUsuarioActivo($email)) {
                $this->warningRedirect(
                    "El correo electrónico ingresado no ha sido activado, activalo desde el correo electrónico enviado para iniciar sesión.", 
                    ['email' => $email],
                );
            }
            SessionManager::auth($usuario['id'], $usuario['nombre'], $usuario['email']);            
            $this->successRedirect(
                'Inicio de sesión exitoso', 
                [],
                '/'
            );
        } else {
            $this->warningRedirect('Credenciales inválidas', ['email' => $email]);
        }
    }

    function logout(): void {
        SessionManager::destroy();
        $this->successRedirect(
            'Sesión cerrada correctamente', 
            [],
            '/'
        );
    }
}