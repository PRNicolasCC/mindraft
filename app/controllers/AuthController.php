<?php
declare(strict_types=1);

require_once 'app/services/EmailService.php';

class AuthController extends Controller {

    public function render(): void{
        $view = 'auth/register';
        $this->view->render($view);
    }
    
    public function login(): void {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            $usuario = UserModel::obtenerPorEmail($email);

            if ($usuario && password_verify($password, $usuario['contraseña'])) {
                SessionManager::set('user', $usuario['id']);
                SessionManager::set('login', true);
                
                header('Location: index.php');
            } /* else {
                $errors[] = 'Invalid credentials';
            } */
        }
    }

    public function register(): void {
        $datosUsuario = UserModel::crear($email, $passHash, $nombre);

        if ($datosUsuario) {
            EmailService::sendWelcomeEmail(
                $datosUsuario['email'], 
                $datosUsuario['token']
            );
        }
    }

    public function logout(): void {
        SessionManager::destroy();
        header('Location: /');
        exit();
    }
}

?>