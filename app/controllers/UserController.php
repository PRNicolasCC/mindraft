<?php
declare(strict_types=1);

require_once 'app/services/EmailService.php';

class UserController extends Controller {
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_MAX_LENGTH = 30;
    private const REDIRECT = 'user/register';

    /* MÉTODOS PÚBLICOS */
    public function __construct(){
        parent::__construct('user');
        $this->view->setRedirect(self::REDIRECT);
    }

    public function render(): void{
        $this->view->render();
    }

    public function register(array $data): void{
        $this->validateUserData([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'confirm_password' => $data['confirm_password'],
        ], true);

        $passHash = $this->hashPassword($data['password']);
        $datosUsuario = $this->model->crear($data['email'], $passHash, $data['username']);

        if (!empty($datosUsuario)) {
            EmailService::sendWelcomeEmail(
                $datosUsuario['email'], 
                $datosUsuario['token']
            );

            $this->view->successRedirect(
                '✅ Usuario registrado correctamente. Por favor, verifica la cuenta con el mensaje enviado a tu correo electrónico para habilitar el inicio de sesión',                 
                [],
                'user/index'
            );
        } else {
            $this->view->cambiarError('Error al registrar el usuario. Por favor contacte al administrador');
        }
    }

    public function activate(array $data): void{
        $this->validateUserData([
            'email' => $data[1],
        ], false);

        $isActive = $this->model->activar($data[1], $data[0]);

        if ($isActive) {
            /* $this->view->successRedirect(
                '🎉 Usuario activado correctamente. Ahora puedes iniciar sesión', 
                ['email' => $data[1]],
                'user/index'
            ); */
            // Es importante detener la salida de cualquier contenido antes de header()
            ob_clean();
            header('Location: index.php');
            exit;
        } else {
            #$this->view->cambiarError('Error al activar el usuario. Por favor solicite un nuevo correo de activación.');
            ob_clean();
            header('Location: ' . $_ENV['DOMAIN'] . '/user');
            exit;
        }
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

    public function logout(): void {
        SessionManager::destroy();
        header('Location: /');
        exit();
    }

    /* MÉTODOS PRIVADOS */
    /* private function redirectWithError(string $mensaje, array $inputs = []): void{
        $this->view->inputs = $inputs;
        $this->view->cambiarError($mensaje, self::REDIRECT);
    } */

    /** 
     * Valida los datos del usuario.
     * @param array $data Los datos del usuario a validar.
     * @param bool $isCreate Indica si es una operación de creación (true) o actualización (false).
     * @return void
     **/
    private function validateUserData(array $data, bool $isCreate = true): void {
        if ($isCreate) {
            $required = ['email', 'username', 'password', 'confirm_password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->view->cambiarError("El campo '$field' es requerido.", $data);
                }
            }
            $this->validateDuplicatedEmail($data);
            $this->validatePassword($data);
        }

        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->view->cambiarError('Formato de correo inválido, por favor ingresa un correo electrónico válido.', $data);
            }
        }
    }

    private function validateDuplicatedEmail(array $data): void {
        if($this->model->obtenerPorEmail($data['email'])) {
            $this->view->cambiarError("El correo electrónico ingresado ya ha sido registrado con otra cuenta.", $data);
        }
    }

    /**
     * Hashea una contraseña usando el algoritmo Argon2ID con opciones seguras.
     * @param string $password La contraseña en texto plano a hashear.
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
            $this->view->cambiarError("Las contraseñas no coinciden.", $data);
        }

        if (strlen($data['password']) < self::PASSWORD_MIN_LENGTH) {
            $this->view->cambiarError("La contraseña debe contener al menos " . self::PASSWORD_MIN_LENGTH . " carácteres.", $data);
        }
        if (strlen($data['password']) > self::PASSWORD_MAX_LENGTH) {
            $this->view->cambiarError("La contraseña debe contener máximo " . self::PASSWORD_MAX_LENGTH . " carácteres.", $data);
        }
        
        if (!preg_match('/[A-Z]/', $data['password'])) {
            $this->view->cambiarError('La contraseña debe contener al menos una mayúscula', $data);
        }
        
        if (!preg_match('/[a-z]/', $data['password'])) {
            $this->view->cambiarError('La contraseña debe contener al menos una minúscula', $data);
        }
        
        if (!preg_match('/\d/', $data['password'])) {
            $this->view->cambiarError('La contraseña debe contener al menos un número', $data);
        }
    }
}

?>