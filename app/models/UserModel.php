<?php
declare(strict_types=1); # volvemos a definir el strict types ya que únicamente se aplica al archivo donde está definida.

class UserModel extends Model {
    private string $table = 'usuarios';

    /* MÉTODOS PÚBLICOS */

    /** 
     * Constructor de la clase UserModel.
     **/
    function __construct() {
        parent::__construct();
    }

    /** 
     * Obtiene el usuario por su email.
     * @param string $email El email del usuario a buscar.
     **/
    public function obtenerPorEmail(string $email): ?array {
        $sql = "SELECT id, email, contraseña FROM {$this->table} WHERE email = :email";

        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    public static function crear(string $email, string $password_hash, string $nombre): ?array {
        $this->validateUserData([
            'email' => $email,
            'nombre' => $nombre,
            'password' => $password_hash,
        ], true);

        $sql = "INSERT INTO {$this->table} (email, contraseña, nombre) VALUES (:email, :pass, :nombre)";
        
        $parametros = [
            'email' => $email,
            'pass'  => $password_hash,
            'nombre' => $nombre
        ];

        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion) {
            $token = bin2hex(random_bytes(16));
           $nuevoId = $this->db->ultimoIdInsertado();

            return [
                'id' => $nuevoId,
                'email' => $email,
                'nombre' => $nombre,
                'token' => $token
            ];
        }

        return [];
        
    }
    
    /* public function obtenerPorId(int $id): ?array {
        // ... (Implementación similar a obtenerPorEmail) ...
    } */


    /* MÉTODOS PRIVADOS */

    /** 
     * Valida los datos del usuario.
     * @param array $data Los datos del usuario a validar.
     * @param bool $isCreate Indica si es una operación de creación (true) o actualización (false).
     **/
    private function validateUserData(array $data, bool $isCreate = true): void {
        if ($isCreate) {
            $required = ['email', 'nombre', 'password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new InvalidArgumentException("El campo '$field' es requerido.");
                }
            }

            #$this->validatePassword($data['password']);
        }

        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Formato de correo inválido.');
            }
        }
    }

    /** 
     * Valida los datos del usuario.
     * @param string $password La contraseña a validar.
     **/
    /* private function validatePassword(string $password): void {
        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            throw new InvalidArgumentException('Password must be at least ' . self::PASSWORD_MIN_LENGTH . ' characters long');
        } */
        
        // Validaciones adicionales de complejidad
        /* if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter');
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one lowercase letter');
        }
        
        if (!preg_match('/\d/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one number');
        } */
    /* } */
    
}

?>