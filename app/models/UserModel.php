<?php
declare(strict_types=1); # volvemos a definir el strict types ya que únicamente se aplica al archivo donde está definida.

class UserModel extends Model {
    private string $table = 'usuarios';
    private string $tableTokens = 'tokens';
    private DateTime $dateTime;

    /* MÉTODOS PÚBLICOS */

    /** 
     * Constructor de la clase UserModel.
     **/
    function __construct() {
        parent::__construct();

        $this->dateTime = new DateTime('now', new DateTimeZone('America/Bogota')); // Se debe tener la misma zona horaria local para no tener discrepancias en la BD
        //$this->dateTime = new DateTime('now', new DateTimeZone('UTC'));
    }

    function crear(string $email, string $password_hash, string $nombre): ?array {
        $sql = "INSERT INTO {$this->table} (email, contraseña, nombre) VALUES (:email, :pass, :nombre)";       
        $parametros = [
            'email' => $email,
            'pass'  => $password_hash,
            'nombre' => $nombre
        ];

        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion > 0) {
            $token = bin2hex(random_bytes(16)); // Genera un token de 16 bytes (32 caracteres hexadecimales)
            $nuevoId = $this->db->ultimoIdInsertado();
            $tokenInsert = $this->almacenarToken($nuevoId, $token);
            return [
                'id' => $nuevoId,
                'email' => $email,
                'nombre' => $nombre,
                'token' => $tokenInsert
            ];
        }
        return null;
    }

    function activar(string $email, string $token): bool {
        $sql = "UPDATE {$this->table} a
                JOIN {$this->tableTokens} b ON a.id = b.usuario_id 
                SET a.is_active = :is_active, b.used = :used, b.used_at = :used_at, b.ip_address = :ip_address
                WHERE a.email = :email AND b.token = :token AND b.used = :not_used";

        $actualizacion = $this->db->ejecutar($sql, [
            'email' => $email, 
            'token' => $token,
            'is_active' => 1,
            'not_used' => 0,
            'used' => 1,
            'used_at' => $this->dateTime->format('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);
        if ($actualizacion > 0) {
            return true;
        }
        return false;
    }

    /** 
     * Obtiene el usuario por su email.
     * @param string $email El email del usuario a buscar.
     **/
    function obtenerPorEmail(string $email): ?array {
        $sql = "SELECT id, email, contraseña FROM {$this->table} WHERE email = :email";

        return $this->db->fetchOne($sql, ['email' => $email]);
    }
    
    /* function obtenerPorId(int $id): ?array {
        // ... (Implementación similar a obtenerPorEmail) ...
    } */


    /**
     * Almacena un token en la base de datos asociado a un usuario.
     * 
     * Este método guarda un token en texto plano asociado al usuario y al tipo de token.
     * 
     * @param int $usuarioId ID del usuario al que se asocia el token
     * @param string $token Token a almacenar (en texto plano)
     * @param string $tipo Tipo de token. Por el momento solo están disponibles 'A' (activación) y 'R' (recuperación)
     * @return int Retorna 1 si el token se almacenó correctamente
     */
    private function almacenarToken(int $usuarioId, string $token, string $tipo='A'): string {
        $fecha_manana = $this->dateTime->modify('+1 day');
        $sqlFormattedTomorrow = $fecha_manana->format('Y-m-d H:i:s');

        $sql = "INSERT INTO {$this->tableTokens} (usuario_id, token, expires_at, tipo) VALUES (:usuario_id, :token, :expires_at, :tipo)";
        $parametros = [
            'usuario_id' => $usuarioId,
            'token' => $token,
            'expires_at' => $sqlFormattedTomorrow,
            'tipo' => $tipo
        ];
        $this->db->ejecutar($sql, $parametros);
        return $token;
    }
    
}

?>