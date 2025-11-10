<?php
declare(strict_types=1);

require_once 'app/services/EmailService.php';

class UserController extends Controller {
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_MAX_LENGTH = 30;

    /* MÉTODOS PÚBLICOS */
    public function __construct(){
        parent::__construct('user');
    }

    public function render(): void{
        $view = 'user/register';
        $this->view->render($view);
    }
    
    public function login(): void {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            #$usuario = UserModel::obtenerPorEmail($email);

            if ($usuario && password_verify($password, $usuario['contraseña'])) {
                SessionManager::set('user', $usuario['id']);
                SessionManager::set('login', true);
                
                header('Location: index.php');
            } /* else {
                $errors[] = 'Invalid credentials';
            } */
        }
    }

    public function register($email, $password, $confirmPassword, $username): void{
        $this->validateUserData([
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'confirm_password' => $confirmPassword,
        ], true);

        $passHash = $this->hashPassword($password);
        $datosUsuario = $this->model->crear($email, $passHash, $username);

        if (!empty($datosUsuario)) {
            EmailService::sendWelcomeEmail(
                $datosUsuario['email'], 
                $datosUsuario['token']
            );

            $this->view->successRedirect(
                'Usuario registrado correctamente. Por favor, verifica la cuenta con el mensaje enviado a tu correo electrónico para habilitar el inicio de sesión', 
                'user/index'
            );
        }
    }

    public function logout(): void {
        SessionManager::destroy();
        header('Location: /');
        exit();
    }


    /* MÉTODOS PRIVADOS */
    private function redirectWithError(string $mensaje, array $inputs = []): void{
        $this->view->cambiarError($mensaje);
        $this->view->inputs = $inputs;
        $this->render();
        exit();
    }

    /** 
     * Valida los datos del usuario.
     * @param array $data Los datos del usuario a validar.
     * @param bool $isCreate Indica si es una operación de creación (true) o actualización (false).
     **/
    private function validateUserData(array $data, bool $isCreate = true): void {
        if ($isCreate) {
            $required = ['email', 'username', 'password', 'confirm_password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->redirectWithError("El campo '$field' es requerido.", $data);
                }
            }
            $this->validateDuplicatedEmail($data);
            $this->validatePassword($data);
        }

        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->redirectWithError('Formato de correo inválido.', $data);
            }
        }
    }

    private function validateDuplicatedEmail(array $data): void {
        if($this->model->obtenerPorEmail($data['email'])) {
            $this->redirectWithError("El correo electrónico ingresado ya ha sido registrado con otra cuenta.", $data);
        }
    }

    /**
     * Hashea una contraseña usando el algoritmo Argon2ID con opciones seguras.
     * * @param string $password La contraseña en texto plano a hashear.
     * @return string|false El hash de la contraseña o false en caso de error.
     */
    private function hashPassword(string $password): string|false {
        // Definimos las opciones de costo recomendadas para Argon2ID.
        // PHP utiliza valores por defecto razonables, pero definirlos es explícito.
        $options = [
            // t_cost (Tiempo): número de iteraciones. El valor por defecto es 4.
            'time_cost' => 4, 
            
            // memory_cost (Memoria): kilobytes de memoria a utilizar. El valor por defecto es 65536 (64 MB).
            'memory_cost' => 65536, 
            
            // threads (Paralelismo): número de hilos. El valor por defecto es 1.
            'threads' => 1 
        ];

        // Utilizamos PASSWORD_ARGON2ID, que es la opción más segura actualmente.
        // password_hash maneja el 'salting' (adición de sal) automáticamente.
        $hash = password_hash($password, PASSWORD_ARGON2ID, $options);
        
        return $hash;
    }

    // ----------------------------------------------------
    // Nota: La verificación se hace con password_verify()
    // ----------------------------------------------------
    /* $isVerified = password_verify($plainPassword, $hashedPassword);

    if ($isVerified) {
        echo "\nVerificación: ¡Contraseña correcta!";
    } else {
        echo "\nVerificación: Contraseña incorrecta.";
    } */

    /** 
     * Valida los datos del usuario.
     * @param string $password La contraseña a validar.
     **/
    private function validatePassword(array $data): void {
        if ($data['password'] !== $data['confirm_password']) {
            $this->redirectWithError("Las contraseñas no coinciden.", $data);
        }

        if (strlen($data['password']) < self::PASSWORD_MIN_LENGTH) {
            $this->redirectWithError("La contraseña debe contener al menos " . self::PASSWORD_MIN_LENGTH . " carácteres.", $data);
        }
        if (strlen($data['password']) > self::PASSWORD_MAX_LENGTH) {
            $this->redirectWithError("La contraseña debe contener máximo " . self::PASSWORD_MAX_LENGTH . " carácteres.", $data);
        }
        
        if (!preg_match('/[A-Z]/', $data['password'])) {
            $this->redirectWithError('La contraseña debe contener al menos una mayúscula', $data);
        }
        
        if (!preg_match('/[a-z]/', $data['password'])) {
            $this->redirectWithError('La contraseña debe contener al menos una minúscula', $data);
        }
        
        if (!preg_match('/\d/', $data['password'])) {
            $this->redirectWithError('La contraseña debe contener al menos un número', $data);
        }
    }
}

?>