<?php
declare(strict_types=1);

require_once BASE_PATH . 'app/services/EmailService.php';

class UserController extends Controller {
    protected const PASSWORD_MIN_LENGTH = 8;
    protected const PASSWORD_MAX_LENGTH = 30;

    function __construct(){
        parent::__construct('user');
        $this->setGetActions([
            'activate',
        ]);
        $this->setTokenMethods([
            'activate',
        ]);
    }

    function render(): void{
        $this->isNotAuth();
        $this->view->render('user/index');
    }

    function register(array $data): void{
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

            $this->successRedirect(
                'Usuario registrado correctamente. Se ha enviado un correo electrónico con un enlace para activar tu cuenta',                 
                [],
                '/'
            );
        } else {
            $this->cambiarError('Error al registrar el usuario. Por favor contacte al administrador');
        }
    }

    function activate(array $data): void{
        $this->validateUserData([
            'email' => $data[1],
        ], false);

        $isActive = $this->model->activar($data[1], $data[0]);

        if (gettype($isActive) === 'string') {
            $this->warningRedirect($isActive, ['email' => $data[1]], '/');
        } else if ($isActive) {
            $this->successRedirect(
                'Usuario activado correctamente. Ahora puedes iniciar sesión', 
                ['email' => $data[1]],
                '/'
            );
        } else {
            $this->warningRedirect('Error al activar el usuario. Por favor solicite un nuevo correo de activación.');
        }
    }

    /** 
     * Valida los datos del usuario.
     * @param array $data Los datos del usuario a validar.
     * @param bool $isCreate Indica si es una operación de creación (true) o actualización (false).
     * @return void
     **/
    protected function validateUserData(array $data, bool $isCreate = true): void {
        if ($isCreate) {
            $required = ['email', 'username', 'password', 'confirm_password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->cambiarError("El campo '$field' es requerido.", $data);
                }
            }
            $this->validateDuplicatedEmail($data);
            $this->validatePassword($data);
        }

        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->cambiarError('Formato de correo inválido, por favor ingresa un correo electrónico válido.', $data);
            }
        }
    }

    protected function validateDuplicatedEmail(array $data): void {
        if($this->model->obtenerPorEmail($data['email'])) {
            $this->cambiarError("El correo electrónico ingresado ya ha sido registrado con otra cuenta.", $data);
        }
    }

    /** 
     * Valida los datos del usuario.
     * @param string $password La contraseña a validar.
     **/
    protected function validatePassword(array $data): void {
        if ($data['password'] !== $data['confirm_password']) {
            $this->cambiarError("Las contraseñas no coinciden.", $data);
        }

        if (strlen($data['password']) < self::PASSWORD_MIN_LENGTH) {
            $this->cambiarError("La contraseña debe contener al menos " . self::PASSWORD_MIN_LENGTH . " carácteres.", $data);
        }
        if (strlen($data['password']) > self::PASSWORD_MAX_LENGTH) {
            $this->cambiarError("La contraseña debe contener máximo " . self::PASSWORD_MAX_LENGTH . " carácteres.", $data);
        }
        
        if (!preg_match('/[A-Z]/', $data['password'])) {
            $this->cambiarError('La contraseña debe contener al menos una mayúscula', $data);
        }
        
        if (!preg_match('/[a-z]/', $data['password'])) {
            $this->cambiarError('La contraseña debe contener al menos una minúscula', $data);
        }
        
        if (!preg_match('/\d/', $data['password'])) {
            $this->cambiarError('La contraseña debe contener al menos un número', $data);
        }
    }

    protected function validateUserActive(array $data): void {
        if (!$this->model->verificarUsuarioActivo($data['email'])) {
            $this->warningRedirect(
                "El correo electrónico ingresado no ha sido activado, activalo desde el correo electrónico enviado para poder restablecer tu contraseña.", 
                ['email' => $data['email']],
            );
        }
    }

    /**
     * Hashea una contraseña usando el algoritmo Argon2ID con opciones seguras.
     * @param string $password La contraseña en texto plano a hashear.
     * @return string|false El hash de la contraseña o false en caso de error.
     */
    protected function hashPassword(string $password): string|false {
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
}
?>